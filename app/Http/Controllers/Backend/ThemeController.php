<?php
/**
 * HomeController
 */

namespace App\Http\Controllers\Backend;

use App\Models\Theme;
use App\Models\Expertise;
use Illuminate\Http\Request;
use mikehaertl\wkhtmlto\Image;
use App\Helpers\Theme\Resume;
use App\Helpers\Theme\ThemeCompiler;
use App\Models\User;
use File;
use Intervention\Image\Facades\Image as ImageIntervention;

class ThemeController extends Controller {
       
    protected $theme;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Theme $theme) {
        $this->theme = $theme;
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    public function index(Request $request) {
        
        $maxPerPage = config('backend.pagination.max_per_page');
        $filter     = $request->query();
        
        if (count($filter)) {
            $themes = $this->theme;
            
            if (isset($filter['q']) && $filter['q'] !== '') {
                $themes = $themes->where(function($query) use($filter) {
                    $query->where('slug', 'like',  '%' . $filter['q'] . '%')
                          ->orWhere('name', 'like', '%' . $filter['q'] . '%')
                          ->orWhere('description', 'like', '%' . $filter['q'] . '%');
                });    
            }
            
            if (isset($filter['status']) && $filter['status'] !== '') {
                $themes = $themes->where('activated', $filter['status']);
            }
            
            if (isset($filter['user_id']) && $filter['user_id'] !== '') {
                $themes = $themes->where('user_id', (int) $filter['user_id']);
            }
            
            $themes = $themes->paginate($maxPerPage);
            
            if (isset($filter['q'])) {
                $themes->appends(['q' => $filter['q']]);
            }
            
            if (isset($filter['status'])) {
                $themes->appends(['status' => $filter['status']]);
            }
            
            if (isset($filter['user_id'])) {
                $themes->appends(['user_id' => $filter['user_id']]);
            }
            
        } else {
            $themes = Theme::paginate($maxPerPage);
        }
        
        return view('backend.themes.index',[
            'themes'     => $themes,
            'maxPerPage' => $maxPerPage,
            'filterQ'    => isset($filter['q'])      ? $filter['q']      : null,
            'filterStat' => isset($filter['status']) ? $filter['status'] : null,
        ]);
    }
    
    public function view($id) {
        
        $theme = Theme::find($id);
        
        if (null === $theme) {
            return redirect()->back();
        }
        
        return view('backend.themes.view',[
            'theme' => $theme
        ]);
    }
    
    public function edit($id) {
        
        $theme     = Theme::find($id);
        $expertise = Expertise::all();
        
        if (null === $theme) {
            return redirect()->back();
        }
        
        return view('backend.themes.edit', [
            'theme'     => $theme,
            'themeExpt' => array_map('intval', unserialize($theme->expertises)),
            'devices'   => ($theme->devices !== '') ? unserialize($theme->devices) : [],
            'expertise' => ['' => _t('theme.upload.themeallExpertises')] + $expertise->pluck('name', 'id')->toArray()
        ]);
    }
    
    public function save(Request $request) {
        if ($request->isMethod('post')) {
            $id    = (int) $request->get('theme_id');
            $theme = Theme::find($id);
            
            if (null === $theme) {
                return redirect()->back();
            }
            
            $rules      = $this->saveThemeValidateRules();
            $devices    = $request->get('devides');
            $themeName  = $request->get('theme_name');
            $expertises = ($request->get('expertise_id')) ? array_filter($request->get('expertise_id')) : [];
            $formData   = $request->all();
            
            if( ! count($expertises)) {
                $rules = remove_rules($rules, 'expertise_id');
            }

            $formData['expertise_id'] = $expertises;

            if ($themeName === $theme->name) {
                $rules = remove_rules($rules, 'theme_name.unique:themes,name');
            }
            
            $validator = validator($request->all(), $rules, $this->saveThemeValidateMessages());
            
            $validator->after(function($validator) use($devices) {
                if (is_array($devices) && count($devices)) {
                    foreach($devices as $device) {
                        if( ! in_array($device, ['desktop', 'tablet', 'mobile'])) {
                            $validator->errors()->add('theme_path', _t('theme.validate.devicesin'));
                        }
                    }
                }
            });
            
            if ($validator->fails()) {
                return back()->withErrors($validator);
            }
            
            $theme->name        = $request->get('theme_name');
            //$theme->slug        = $request->get('theme_slug');
            $theme->version     = $request->get('theme_version');
            $theme->description = $request->get('theme_desc');
            $theme->devices     = (is_array($devices))    ? serialize($devices)    : '';
            $theme->expertises  = (is_array($expertises)) ? serialize($expertises) : '';
            $theme->tags        = $request->get('theme_tags');
            $theme->save();
            
            $storagePath = config('frontend.themesFolder') . '/' . $theme->slug;
            
            $this->uploadThemeImg([
                'file'         => $request->file('thumbnail'),
                'type'         => 'thumbnail',
                'storage_path' => $storagePath
            ]);
            
            $this->uploadThemeImg([
                'file'         => $request->file('screenshot'),
                'type'         => 'screenshot',
                'storage_path' => $storagePath
            ]);
            
            return back();
        }
    }
    
    public function updateStatus(Request $request) {
        $themeId = (int) $request->get('theme_id');
        $theme   = Theme::find($themeId);
        
        if (null === $theme) {
            return redirect()->back();
        }
        
        if ($theme->activated) {
            $theme->activated = 0;
        } else {
            $theme->activated = 1;
        }
        
        $theme->save();
        
        return redirect()->back();
    }

    public function generateScreenshot() {

        try {

            $themes = Theme::all();
            if (count($themes)) {

                $defaultUserId    = config('backend.theme.default_user_id');
                $themeFolder      = config('frontend.themesFolder');
                $screenshotName   = config('backend.theme.screenshot.name');
                $screenshotHeight = config('backend.theme.screenshot.height');
                $screenshotWidth  = config('backend.theme.screenshot.width');
                $thumbnailName    = config('backend.theme.thumbnail.name');
                $thumbnailHeight  = config('backend.theme.thumbnail.height');
                $thumbnailWidth   = config('backend.theme.thumbnail.width');
                $resume           = $this->generateResumeData($defaultUserId);

                foreach ($themes as $theme) {

                    $compiler       = new ThemeCompiler($resume, $theme->slug);
                    $contents       = $compiler->compileDownload();
                    $wkhtmlToImage  = new Image();
                    $screenshotPath = $themeFolder . '/' . $theme->slug . '/' . $screenshotName;
                    $thumbnailPath  = $themeFolder . '/' . $theme->slug . '/' . $thumbnailName;

                    $wkhtmlToImage->setPage($contents);
                    $wkhtmlToImage->saveAs($screenshotPath);

                    $image = ImageIntervention::make($screenshotPath)->orientate();
                    $image->crop($image->width(), $image->width() + 200, 0, 0)->resize($screenshotWidth, $screenshotHeight);
                    $image->save($screenshotPath);

                    $image->resize($thumbnailWidth, $thumbnailHeight);
                    $image->save($thumbnailPath);
                }
            }
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    
    protected function uploadThemeImg($options = []) {
        $type        = isset($options['type'])         ? $options['type']         : '';
        $file        = isset($options['file'])         ? $options['file']         : '';
        $storagePath = isset($options['storage_path']) ? $options['storage_path'] : '';

        if($file && $file->isValid()) {

            $fileStr   = random_string(16, $available_sets = 'lud');
            $fileExt   = $file->getClientOriginalExtension();
            $fileName  = $fileStr . '.' . $fileExt;

            try {
                if ($file->move($storagePath, $fileName)) {
                    delete_file($storagePath . "/{$type}.png");
                    File::move($storagePath . '/' . $fileName, $storagePath . "/{$type}.png");
                }
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Save theme validate rules.
     * 
     * @return array
     */
    protected function saveThemeValidateRules() {
        return [
            'thumbnail'     => 'mimes:png|dimensions:width=200,height=150',
            'screenshot'    => 'mimes:png|dimensions:width=800,height=600',
            'theme_name'    => 'required|alpha_spaces|min:3|max:250|unique:themes,name',
            'theme_slug'    => 'required|min:2|max:250',
            'theme_version' => 'required|min:2|max:10',
            'theme_desc'    => 'required|min:20',
            'expertise_id'  => 'exists:expertises,id'
        ];
    }
    
    protected function saveThemeValidateMessages() {
        return [
            'theme_name.alpha_spaces' => _t('theme.validate.themenamealdash'),
        ];  
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
}