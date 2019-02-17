<?php
namespace App\Http\Controllers\Frontend;

use App\Models\UserProfile;
use App\Models\User;
use App\Models\Theme;
use App\Helpers\Theme\Resume;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Theme\ThemeCompiler;
use mikehaertl\wkhtmlto\Pdf;
use mikehaertl\pdftk\Pdf as Pdftk;
use App\Helpers\Pdfjam\Pdfjam;

class ResumeController extends Controller {

    /**
     * @param $slug
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Throwable
     */
    public function index($slug) {

        $userProfile = UserProfile::where('slug', $slug)->where('publish', 1)->first();
        $themeName   = config('frontend.defaultThemeName');

        if (auth()->check() && user()->userProfile->slug === $slug) {
            $userProfile = user()->userProfile;
        }

        if (null === $userProfile) {
            abort(404);
        }

        if ($userProfile->theme_id) {
            $theme = Theme::find($userProfile->theme_id);
            if (null !== $theme) {
                $themeName = $theme->slug;
            }
        }

        $resume   = $this->generateResumeData($userProfile->user_id);
        $compiler = new ThemeCompiler($resume, $themeName);
        $contents = $compiler->compile();

        if ( ! $contents) {
            abort(404);
        }

        if (auth()->check()) {
            $injectHtml = view('frontend.resume.html-injection', ['slug' => $themeName])->render();
            $response   = str_replace('</body>', $injectHtml . '</body>', $contents );
        } else {
            $response = $contents;
        }

        return new Response($response);
    }

    /**
     * Preview theme
     *
     * @param $slug
     * @return Response
     * @throws \Throwable
     */
    public function preview($slug) {

        if (null === Theme::where('slug', $slug)->first()) {
            throw new NotFoundHttpException;
        }

        $resume     = $this->generateResumeData(user_id());
        $compiler   = new ThemeCompiler($resume, $slug);
        $contents   = $compiler->compile();
        $injectHtml = view('frontend.resume.html-injection', ['slug' => $slug])->render();
        $response   = str_replace('</body>', $injectHtml . '</body>', $contents );

        return response($response);
    }

    /**
     * Download CV as PDF
     * Base on resume's height to download, the script is:
     * 1. If the height is smaller or equal 1320 => that is one-page resume -> download without margin-top or bottom
     * 2. If the height is smaller or equal (1320*2=2640) => that is 2-pages resume -> download the resume
     * with fixed mergin (being configed in config frontend, default is 5px)
     * 3.
     *
     * @param string $slug
     * @param int    $height The height from preview resume page.
     * @throws NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function download($slug, $height) {

        if (empty($slug) || null === Theme::where('slug', $slug)->first() || ! $height) {
            abort(404);
        }

        try {
            $compiler            = new ThemeCompiler($this->generateResumeData(user_id(), true), $slug);
            $contents            = $compiler->compile();
            $configs             = $compiler->getConfig();
            $pdfMaxHeightPerPage = config('frontend.pdfMaxHeightPerPage');
            $pdfDownloadPrefix   = config('frontend.pdfDownloadPrefix');
            $tmpPath             = config('frontend.tmpFolder');
            $pdfConfig           = isset($configs['pdf']) ? $configs['pdf'] : [];
            $downloadFaster      = (isset($pdfConfig['download_faster']) && $pdfConfig['download_faster']) ? true : false;

            if ($downloadFaster) {
                //wkhtmltopdf download with margin top and bottom for all pages.
                $this->_wkhtmltopdfDownload($contents, $this->_getWkhtmltopdfFasterDownloadConfig());
                exit();
            } elseif ($height <= $pdfMaxHeightPerPage) {
                // Resume's height <= Pdf-Max-Height-Per-Page -> it's an one-page resume -> download without margin-top or bottom.
                $this->_wkhtmltopdfDownload($contents);
                exit();
            } elseif ($height <= ($pdfMaxHeightPerPage*2)) {
                // Resume's height <= 2*Pdf-Max-Height-Per-Page -> it's a 2-pages resume -> download with 2-pages wkhtmltopdf config
                $pdfConfigs = $this->_getWkhtmltopdf2PagesResumeConfigs();
                $alphabet   = 'ABCDEFGHJKMNPQRSTUVWXYZ';

                for ($page = 0; $page < 2; $page++) {
                    $wkhtmltopdf = new Pdf($pdfConfigs[$page]);
                    $tmpFileName = generate_filename($tmpPath, 'pdf', ['prefix' => 'tmppdf-' . $alphabet[$page] . '-']);

                    $wkhtmltopdf->addPage($contents);
                    $wkhtmltopdf->saveAs($tmpPath . '/' . $tmpFileName);

                    $mergeConfigs[$alphabet[$page]] = $tmpPath . '/' . $tmpFileName;
                    $tmpFiles[$alphabet[$page]]     = ['page' => $page + 1];
                }

                if (count($tmpFiles)) {
                    $pdftk          = new Pdftk($mergeConfigs);
                    $mergedFileName = generate_filename($tmpPath, 'pdf', ['prefix' => $pdfDownloadPrefix]);

                    foreach($tmpFiles as $alias => $file) {
                        $pdftk->shuffle($file['page'], null, $alias);
                    }

                    $pdftk->saveAs($tmpPath . '/' . $mergedFileName);

                    delete_file($mergeConfigs);

                    $this->_downloadPdf($mergedFileName, $tmpPath . '/' . $mergedFileName);
                }
            } else {
                $pdfDefaultMargin           = config('frontend.pdfDefaultMargin');
                $mergedFileName             = generate_filename($tmpPath, 'pdf', ['prefix' => $pdfDownloadPrefix]);
                $tmpInput                   = generate_filename($tmpPath, 'pdf', ['prefix' => 'tmppdf-']);
                $tmpOutput                  = generate_filename($tmpPath, 'pdf', ['prefix' => 'tmppdf-']);
                $pdfConfig                  = config('frontend.wkhtmltopdf');
                $pdfConfig['margin-bottom'] = $pdfDefaultMargin;
                $wkhtmltopdf                = new Pdf($pdfConfig);

                $wkhtmltopdf->addPage($contents);
                $wkhtmltopdf->saveAs($tmpPath . '/' . $tmpInput);

                $pdfjam = new Pdfjam($tmpPath . '/' . $tmpInput);
                $pdfjam->setDefaultOptions();
                $pdfjam->setOutput($tmpPath . '/' . $tmpOutput);
                $pdfjam->execute();

                $pdfjam->setMargin(0, 0, ($pdfDefaultMargin/10), 0);
                $pdfjam->setOutput($tmpPath . '/' . $tmpInput);
                $pdfjam->execute();

                $pdf = new Pdftk([
                    'A' => $tmpPath . '/' . $tmpInput,
                    'B' => $tmpPath . '/' . $tmpOutput
                ]);

                $pdf->shuffle(1, null, 'A');
                $pdf->shuffle(2, 'end', 'B');
                $pdf->saveAs($tmpPath . '/' . $mergedFileName);

                delete_file([$tmpPath . '/' . $tmpInput, $tmpPath . '/' . $tmpOutput]);

                $this->_downloadPdf($mergedFileName, $tmpPath . '/' . $mergedFileName);

            }
        } catch (\Exception $e) {
            throw \Exception('Whoop!! Something went wrong, please try again.');
        }
    }

    /**
     * Wkhtmltopdf download
     *
     * @param string $html Html string to convert to pdf
     * @param array $wkhtmltopdfConfig config
     * @throws NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    protected function _wkhtmltopdfDownload($html, $wkhtmltopdfConfig = array()) {
        $pdfDownloadPrefix = config('frontend.pdfDownloadPrefix');
        $pdfDefaultConfig  = (count($wkhtmltopdfConfig)) ? $wkhtmltopdfConfig : config('frontend.wkhtmltopdf');
        $pdf               = new Pdf($pdfDefaultConfig);
        $fileName          = $pdfDownloadPrefix . random_string(12, 'lud') . '.pdf';

        $pdf->addPage($html);

        if ( ! $pdf->send($fileName)) {
            abort(404);
        }
    }

    /**
     * Generate resume data for show CV
     *
     * @param $user_id
     * @return Resume
     */
    protected function generateResumeData($user_id, $isDownload = false) {

        $resume = new Resume();
        $user   = User::find($user_id);

        $resume->setEmail($user->email);
        $resume->setFirstName($user->userProfile->first_name);
        $resume->setLastName($user->userProfile->last_name);
        $resume->setAvatarImages($user->userProfile->avatar_image);
        $resume->setCoverImages($user->userProfile->cover_image);
        $resume->setDob($user->userProfile->day_of_birth);
        $resume->setAboutMe($user->userProfile->about_me);
        $resume->setMaritalStatus(collect($user->userProfile->maritalStatus));
        $resume->setGender(collect($user->userProfile->gender));
        $resume->setCountry(collect($user->userProfile->country));
        $resume->setCity($user->userProfile->city_name);
        $resume->setDistrict(collect($user->userProfile->district));
        $resume->setWard(collect($user->userProfile->ward));
        $resume->setStreetName($user->userProfile->street_name);
        $resume->setPhoneNumber($user->userProfile->phone_number);
        $resume->setWebsite($user->userProfile->website);
        $resume->setSocialNetworks($user->userProfile->social_network);
        $resume->setSkills($user->skills);
        $resume->setEmployments($user->employmentHistories);
        $resume->setEducations($user->educations);
        $resume->setExpectedJob($user->userProfile->expected_job);
        $resume->setHobbies($user->userProfile->hobbies);
        $resume->setDownload($isDownload);

        return $resume;
    }

    /**
     * @param string $pdfFile Pdf file path
     *
     * @return array Pdf metadata
     */
    private function _getPdfMetadata($pdfFile) {

        // Pdf's metadata is a string that contains new line \n
        $pdftkCheckPage = new Pdftk($pdfFile);
        $metadataRaw    = nl2br($pdftkCheckPage->getData()); //So replate new line with <br>
        $metadataRaw    = trim(preg_replace('/\s+/', ' ', $metadataRaw)); // Then remove other new lines \n
        $metadataRaw    = explode('<br />', $metadataRaw);
        $metadata       = [];

        if (count($metadataRaw)) {
            foreach($metadataRaw as $field) {
                $fieldRaw = explode(':', $field);
                $metadata[trim($fieldRaw[0])] = isset($fieldRaw[1]) ? trim($fieldRaw[1]) : '';
            }
        }

        return $metadata;
    }

    private function _downloadPdf($filename, $filePath) {
        header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT');
        header('Accept-Ranges: bytes');  // For download resume
        header('Content-Length: ' . filesize($filePath));  // File size
        header('Content-Encoding: none');
        header('Content-Type: application/pdf');  // Change this mime type if the file is not PDF
        header('Content-Disposition: attachment; filename=' . $filename);  // Make the browser display the Save As dialog
        return readfile($filePath);  //this is necessary in order to get it to actually download the file, otherwise it will be 0Kb
    }

    /**
     * Get wkhtmltopdf's config for 2 pages resume
     *
     * @return array
     */
    private function _getWkhtmltopdf2PagesResumeConfigs() {
        $wkhtmltopdfConfig                  = config('frontend.wkhtmltopdf');
        $config                             = [];
        $wkhtmltopdfConfig['margin-bottom'] = config('frontend.pdfDefaultMargin');
        $config[]                           = $wkhtmltopdfConfig;//Page number 1 with margin-bottom and no margin-top.
        $wkhtmltopdfConfig['margin-bottom'] = 0;
        $wkhtmltopdfConfig['margin-top']    = config('frontend.pdfDefaultMargin');
        $config[]                           = $wkhtmltopdfConfig;//Page number 2 with margin-top and no margin-bottom.

        return $config;
    }

    /**
     * get wkhtmltopdf with margin-top and bottom.
     *
     * @return array
     */
    private function _getWkhtmltopdfFasterDownloadConfig() {
        $wkhtmltopdfConfig                  = config('frontend.wkhtmltopdf');
        $wkhtmltopdfConfig['margin-top']    = config('frontend.pdfDefaultMargin');
        $wkhtmltopdfConfig['margin-bottom'] = config('frontend.pdfDefaultMargin');

        return $wkhtmltopdfConfig;
    }
}