<?php
namespace App\Http\Controllers\Frontend;

use App\Models\UserProfile;
use App\Models\User;
use App\Models\Theme;
use App\Helpers\Theme\Resume;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Theme\ThemeCompiler;
use Illuminate\Filesystem\Filesystem;
use mikehaertl\wkhtmlto\Pdf;
use mikehaertl\pdftk\Pdf as Pdftk;

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

        return new Response($response);
    }

    /**
     * Download CV as PDF
     *
     * @param $slug
     * @throws NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function download($slug, $height = null) {

        if (null === Theme::where('slug', $slug)->first()) {
            abort(404);
        }

        $resume       = $this->generateResumeData(user_id());
        $compiler     = new ThemeCompiler($resume, $slug);
        $contents     = $compiler->compileDownload();
        $pdfConfigs   = $compiler->getConfigPdf();
        $pdfMaxHeight = config('frontend.pdfMaxHeight');

        if ($height && $height <= $pdfMaxHeight) {
            $pdfDefaultCog = config('frontend.wkhtmltopdf');
            $pdf           = new Pdf($pdfDefaultCog);
            $fileName      = 'cv_' . $resume->getFirstName() . $resume->getLastName() . '_' . md5($resume->getEmail()) . '.pdf';

            $pdf->addPage($contents);

            if ( ! $pdf->send($fileName)) {
                abort(404);
            }

            exit();
        }

        if (isset($pdfConfigs['marginEachPage']) && $pdfConfigs) {
            unset($pdfConfigs['marginEachPage']);

            if (count($pdfConfigs)) {
                $tmpFiles     = [];
                $mergeConfigs = [];
                $alphabet     = 'ABCDEFGHJKMNPQRSTUVWXYZ';
                $pageNumbers  = 0;
                $metadata     = null;

                foreach ($pdfConfigs as $k => $one) {
                    $tmpPath     = config('frontend.tmpFolder');
                    $tmpFileName = generate_filename($tmpPath, 'pdf', ['prefix' => 'tmppdfcv_' . $resume->getFirstName() . $resume->getLastName() . '_']);
                    $wkhtmltopdf = new Pdf($one['config']);

                    $wkhtmltopdf->addPage($contents);

                    if ( ! $wkhtmltopdf->saveAs($tmpPath . '/' . $tmpFileName)) {
                        abort(404);
                    }

                    if (is_null($metadata)) {
                        $metadata    = $this->_getPdfMetadata($tmpPath . '/' . $tmpFileName);
                        $pageNumbers = isset($metadata['NumberOfPages']) ? $metadata['NumberOfPages'] : 0;
                    }

                    if ($pageNumbers && $pageNumbers < 2) {
                        $this->_downloadPdf($tmpFileName, $tmpPath . '/' . $tmpFileName);
                    }

                    $mergeConfigs[$alphabet[$k]] = $tmpPath . '/' . $tmpFileName;
                    $tmpFiles[$alphabet[$k]]     = ['page' => $one['page']];
                }

                if (count($mergeConfigs)) {
                    $pdftk          = new Pdftk($mergeConfigs);
                    $mergedFileName = 'cv_' . $resume->getFirstName() . $resume->getLastName() . '_' . md5($resume->getEmail()) . '.pdf';
                    $mergedFilePath = $tmpPath . '/' . $mergedFileName;

                    foreach($tmpFiles as $alias => $file) {
                        if($file['page'] <= $pageNumbers) {
                            $pdftk->shuffle($file['page'], null, $alias);
                        }
                    }

                    $pdftk->saveAs($mergedFilePath);

                    delete_file($mergeConfigs);

                    $this->_downloadPdf($mergedFileName, $mergedFilePath);
                }

            }

        } else {
            $pdf      = new Pdf($pdfConfigs);
            $fileName = 'cv_' . $resume->getFirstName() . $resume->getLastName() . '_' . md5($resume->getEmail()) . '.pdf';
            $pdf->addPage($contents);
            if ( ! $pdf->send($fileName)) {
                abort(404);
            }

            exit();
        }
    }

    /**
     * Generate resume data for show CV
     *
     * @param $user_id
     * @return Resume
     */
    protected function generateResumeData($user_id) {

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
        $metadataRaw    = trim(preg_replace('/\s+/', ' ', $metadataRaw)); // Then remove  other new lines \n
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
}