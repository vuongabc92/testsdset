<?php
/**
 * Setting Controller
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as ImageIntervention;
use App\Models\UserProfile;
use App\Models\Gender;
use App\Models\Country;
use App\Models\Expertise;
use App\Models\EmploymentHistory;
use App\Models\Education;
use App\Models\UserSkill;
use App\Models\Skill;
use App\Models\MaritalStatus;
use App\Models\Theme;
use App\Helpers\SaveSettings;
use DB;
use Validator;
use Log;
use File;


class SettingsController extends Controller {
    
    use SaveSettings;
        
    /**
     * Display settings page
     * 
     * @return Response
     */
    public function index() { 
        $genders             = ['' => _t('setting.profile.sextell')];
        $maritalStatuses     = ['' => _t('setting.profile.marital')];
        $availableSocial     = ['' => _t('setting.profile.social_selector')] + config('frontend.availableSocial');
        
        $userProfile         = is_null(user()->userProfile) ? new UserProfile() : user()->userProfile;
        $genderName          = Gender::find($userProfile->gender_id);
        $expertises          = Expertise::all()->sortBy('name')->pluck('name', 'id')->toArray();
        $countries           = Country::all()->sortBy('country_name')->pluck('country_name', 'id')->toArray();
//        $cities              = $this->_getCityByCountryId($userProfile->country_id, $toArray = true);
//        $districts           = $this->_getDistrictByCityId($userProfile->city_id, $toArray = true);
//        $wards               = $this->_getWardByDistrictId($userProfile->district_id, $toArray = true);
        $employmentHistories = user()->employmentHistories->sortByDesc('is_current')->sortByDesc('start_date')->all();
        $educations          = user()->educations->sortByDesc('start_date')->all();
        
        if (Gender::all()) {
            foreach (Gender::all() as $gender) {
                $genders[$gender->id] = $gender->gender_name;
            }
        }
        
        if (MaritalStatus::all()) {
            foreach (MaritalStatus::all() as $marital) {
                $maritalStatuses[$marital->id] = $marital->name;
            }
        }
        
        return view('frontend.settings.index', [
            'userProfile'         => $userProfile,
            'avatarMedium'        => $userProfile->avatar(),
            'coverMedium'         => $userProfile->cover(),
            'genders'             => $genders,
            'gender'              => ( ! is_null($genderName)) ? $genderName->gender_name : null,
            'countries'           => ['' => _t('setting.profile.country')] + $countries,
            'expertises'          => ['' => _t('setting.profile.pickexpertise')] + $expertises,
//            'cities'              => $cities,
//            'districts'           => $districts,
//            'wards'               => $wards,
            'employmentHistories' => $employmentHistories,
            'educations'          => $educations,
            'maritalStatuses'     => $maritalStatuses,
            'availableSocial'     => $availableSocial,
            'birthdate'           => birthdate(),
            'socialList'          => social_profile_list(),
            'fromFb'              => (user()->password === null || user()->password === '')
        ]);
    }
        
    /**
     * Change the profile's status: publish or unpublish
     * 
     * @param Request $request
     * 
     * @return AJAX 
     */
    public function publishProfile(Request $request) {
        if ($request->ajax() && $request->isMethod('POST')) {
            
            $userProfile     = user()->userProfile;
            $publish_request = ($request->get('publish_state') === 'true') ? true : false;
            
            if (is_null($userProfile)) {
                $userProfile          = new UserProfile();
                $userProfile->user_id = user()->id;
            }
            
            $userProfile->publish = $publish_request;
            $userProfile->save();
            
            return pong(['publish' => $userProfile->publish]);
        }
    }
    
    /**
     * Upload avatar image.
     * 
     * @param Request $request
     * 
     * @return AJAX
     */
    function uploadAvatar(Request $request) {
        if ($request->isMethod('POST')) {
            $rules     = $this->_getAvatarRules();
            $messages  = $this->_getAvatarMessages();
            $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return file_pong(['message' => $validator->errors()->first()], _error(), 403);
            }
            
            if ($request->file('__file')->isValid()) {
                $names = $this->_uploadAvatarImage($request->file('__file'));
                
                if (is_array($names) && count($names)) {
                    $userProfile  = user()->userProfile;
                    $sizes        = config('frontend.avatarSizes');
                    $storagePath  = config('frontend.avatarsFolder');
                    $responseName = (isset($names[$sizes['small']['w']])) ? $names[$sizes['small']['w']] : '';
                    
                    if (is_null($userProfile)) {
                        $userProfile          = new UserProfile();
                        $userProfile->user_id = user()->id;
                    } else {
                        
                        $avatarImages = unserialize($userProfile->avatar_image);
                        if (is_array($avatarImages) && count($avatarImages)) {
                            
                            foreach ($avatarImages as $img) {
                                delete_file($storagePath . '/' . $img);
                            }
                        }
                    }

                    $userProfile->avatar_image = serialize($names);
                    $userProfile->save();

                    return file_pong(['avatar_medium' => $storagePath . '/' . $responseName]);
                }
            }
            
            return file_pong(['message' => _t('oops')], _error(), 403);
        }
    }
    
    /**
     * Upload cover image.
     * 
     * @param Request $request
     * 
     * @return AJAX
     */
    function uploadCover(Request $request) {
        if ($request->isMethod('POST')) {
            $rules     = $this->_getCoverRules();
            $messages  = $this->_getCoverMessages();
            $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return file_pong(['message' => $validator->errors()->first()], _error(), 403);
            }
            
            if ($request->file('__file')->isValid()) {
                
                $names = $this->_uploadCoverImage($request->file('__file'));
                
                if (is_array($names) && count($names)) {
                    $userProfile  = user()->userProfile;
                    $sizes        = config('frontend.coverSizes');
                    $storagePath  = config('frontend.coversFolder');
                    $responseName = (isset($names[$sizes['small']['w']])) ? $names[$sizes['small']['w']] : '';

                    if (is_null($userProfile)) {
                        $userProfile          = new UserProfile();
                        $userProfile->user_id = user()->id;
                    } else {
                        $coverImages = unserialize($userProfile->cover_image);
                        if (is_array($coverImages) && count($coverImages)) {
                            foreach ($coverImages as $img) {
                                delete_file($storagePath . '/' . $img);
                            }
                        }
                    }

                    $userProfile->cover_image = serialize($names);
                    $userProfile->save();
                    
                    
                    return file_pong(['cover_medium' => $storagePath . '/' . $responseName]);
                }
            }
            
            return file_pong(['message' => _t('oops')], _error(), 403);
        }
    }

    /**
     * Save settings
     * 
     * @param Request $request
     * 
     * @return JSON
     */
    public function saveInfo(Request $request) {
        if ($request->ajax() && $request->isMethod('POST')) {
            
            switch ($request->get('type')) {
                case '_EMAIL':
                    $save = $this->saveEmail($request);
                    break;
                    
                case '_SLUG':
                    $save = $this->saveSlug($request);
                    break;
                
                case '_PASS':
                    $save = $this->savePassword($request);
                    break;
                
                case '_PERSONAL':
                    $save = $this->savePersonalInfo($request);
                    break;
                
                case '_CONTACT':
                    $save = $this->saveContactInfo($request);
                    break;
                
                case '_EXPERTISE':
                    $save = $this->saveExpertise($request);
                    break;
                
                case '_EMPLOYMENT':
                    $save = $this->saveEmployment($request);
                    break;
                
                case '_EDUCATION':
                    $save = $this->saveEducation($request);
                    break;
                
                case '_SKILL':
                    $save = $this->saveSkill($request);
                    break;
                
                case '_THEME':
                    $save = $this->saveTheme($request);
                    break;

                default:
                    $save = false;
                    break;
            }
            
            $result = $this->_saveInfoResult($save);
            
            if (in_array(_error(), $result)) {
                return pong(['message' => $result['message']], _error(), $result['code']);
            }
            
            return pong($result);
        }
    }
    
    public function createAddressSelectData(Request $request) {
        if ($request->ajax() && $request->isMethod('POST')) {
            
            $findId   = (int) $request->get('find_id');
            $city     = [$this->_getDefaultAddress('city')];
            $district = [$this->_getDefaultAddress('district')];
            $ward     = [$this->_getDefaultAddress('ward')];
            
            switch ($request->get('target')) {
                case 'city':
                    $city = $this->_getCityByCountryId($findId);
                    break;
                    
                case 'district':
                    $district = $this->_getDistrictByCityId($findId);
                    break;
                
                case 'ward':
                    $ward = $this->_getWardByDistrictId($findId);
                    break;
            }
            
            return pong(['options' => ['city' => $city, 'district' => $district, 'ward' => $ward]]);
        }
    }
    
    /**
     * Get employment history by id
     * 
     * @param Request $request
     * @param int     $id
     * 
     * @return JSON
     */
    public function getEmploymentHistoryById(Request $request, $id) {
        if ($request->ajax() && $request->isMethod('GET')) {
           
            $employment = EmploymentHistory::find($id);
            if (is_null($employment)) {
                return pong(['message' => _t('oops')],  _error(), 403);
            }
            
            $isCurrent = $employment->is_current;
            $startDate = new \DateTime($employment->start_date);
            $endDate   = ($isCurrent) ? false : new \DateTime($employment->end_date);
            
            return pong(['data' => [
                'id'          => $employment->id,
                'name'        => $employment->company_name,
                'position'    => $employment->position,
                'achievement' => $employment->achievement,
                'start_month' => (strlen($sm = $startDate->format('d')) === 2) ? $sm : '0' . $sm,
                'start_year'  => $startDate->format('Y'),
                'end_month'   => ($isCurrent) ? null : ((strlen($em = $endDate->format('d')) === 2) ? $em : '0' . $em),
                'end_year'    => ($isCurrent) ? null : $endDate->format('Y'),
                'website'     => $employment->company_website,
                'is_current'  => $employment->is_current
            ]]);
        }
    }
    
    /**
     * Remove employment history by id
     * 
     * @param Request $request
     * 
     * @return JSON
     */
    public function removeEmploymentHistoryById(Request $request) {
        if ($request->ajax() && $request->isMethod('DELETE')) {
           
            $employment = EmploymentHistory::find((int) $request->get('id'));
            if (is_null($employment)) {
                return pong(['message' => _t('oops')], _error(), 403);
            }
            
            $employment->delete();
            
            return pong(['message' => _t('saved')]);
        }
    }
    
    /**
     * Remove education history by id
     * 
     * @param Request $request
     * 
     * @return JSON
     */
    public function removeEducationHistoryById(Request $request) {
        if ($request->ajax() && $request->isMethod('DELETE')) {

            $education = Education::find((int) $request->get('id'));
            if (is_null($education)) {
                return pong(['message' => _t('oops')], _error(), 403);
            }
            
            $education->delete();
            
            return pong(['message' => _t('saved')]);
        }
    }
    
    /**
     * Get education history by id
     * 
     * @param Request $request
     * @param int     $id
     * 
     * @return JSON
     */
    public function getEducationHistoryById(Request $request, $id) {
        if ($request->ajax() && $request->isMethod('GET')) {
           
            $education = Education::find($id);
            if (is_null($education)) {
                return pong(['message' => _t('oops')], _error(), 403);
            }
            
            $startDate = new \DateTime($education->start_date);
            $endDate   = new \DateTime($education->end_date);
            
            return pong(['data' => [
                'id'                 => $education->id,
                'name'               => $education->college_name,
                'subject'            => $education->subject,
                'qualification_id'   => $education->qualification_id,
                'qualification_name' => $education->qualification->name,
                'start_month'        => (strlen($sm = $startDate->format('d')) === 2) ? $sm : '0' . $sm,
                'start_year'         => $startDate->format('Y'),
                'end_month'          => (strlen($em = $endDate->format('d')) === 2) ? $em : '0' . $em,
                'end_year'           => $endDate->format('Y')
            ]]);
        }
    }

    /**
     * Delete user skill
     * 
     * @param Request $request
     * 
     * @return JSON
     */
    public function killTag(Request $request) {
        if ($request->ajax() && $request->isMethod('DELETE')) {
            $userSkill = UserSkill::find((int) $request->get('id'));
            if (null === $userSkill) {
                return pong(['message' => _t('oops')], _error(), 403);
            }
            
            if ($userSkill->skill->userSkills->count() === 1) {
                $userSkill->skill->delete();
            }
            
            $userSkill->delete();
            
            return pong(['message' => _t('saved')]);
        }
    }
    
    /**
     * Delete user skill
     * 
     * @param Request $request
     * 
     * @return JSON
     */
    public function killSocial(Request $request) {
        if ($request->ajax() && $request->isMethod('DELETE')) {
            $userProfile   = user()->userProfile;
            $socialLinks   = unserialize($userProfile->social_network);
            $socialId      = $request->get('id');
            $socialAllowed = config('frontend.availableSocial');
            
            if (isset($socialAllowed[$socialId])) {
                if (count($socialLinks)) {
                    if (isset($socialLinks[$socialId])) {
                        unset($socialLinks[$socialId]);
                        $userProfile->social_network = serialize($socialLinks);
                        $userProfile->save();
                    }
                }
                
                return pong(['message' => _t('saved')]);
            }
            
            return pong(['message' => _t('oops')],  _error(), 403);
        }
    }
    
    /**
     * Searching skill by name
     * 
     * @param Request $request
     * @param string  $keyword
     * 
     * @return JSON
     */
    public function searchSkill(Request $request, $keyword) {
        if ($request->ajax() && $request->isMethod('GET')) {
            $skills = Skill::where('name', 'like', '%' . $keyword . '%')->limit(10)->get();
            
            if ($skills->count() > 0) {
                return pong(['skills' => $skills->toArray(), 'total' => $skills->count()]);
            }
            
            return pong(['total' => $skills->count()]);
        }
    }
    
    /**
     * List themes
     * 
     * @return void
     */
    public function theme() {
        
        $expertises   = Expertise::all()->sortBy('name')->pluck('name', 'id')->toArray();
        $currentTheme = user()->userProfile->theme;
        $perPage      = config('frontend.lazy_loading.per_page');
        $themes       = Theme::where('user_id', user()->id)->paginate($perPage);
        
        if ($currentTheme === null) {
            $currentTheme = Theme::where('slug', config('frontend.defaultThemeName'))->first();
        }

        return view('frontend.settings.theme', [
            'expertises'   => ['' => _t('theme.upload.themeallExpertises')] + $expertises,
            'themes'       => $themes,
            'currentTheme' => $currentTheme,
        ]);
    }
    
    public function lazyLoadTheme(Request $request) {
        $perPage  = config('frontend.lazy_loading.per_page');
        $page     = (int) $request->query('page');
        $skip     = $perPage * ($page - 1);
        $themes   = Theme::where('activated', 1)->skip($skip)->take($perPage)->get();
        $nextPage = Theme::where('activated', 1)->skip($skip + $perPage)->take(1)->get();
        
        return pong(['html' => view('frontend.settings.theme-item', ['themes' => $themes])->render(), 'is_next' => $nextPage->count()]);
    }
    
    public function themeDetails($slug, Request $request) {
        
        $theme = Theme::where('slug', $slug)->first();
        
        if ( $theme !== null ) {
            $userProfile = $theme->user->userProfile;
            
            if ($request->ajax()) {
                return view('frontend.inc.theme-details', [
                    'theme'       => $theme,
                    'userProfile' => $userProfile
                ]);
            } else {
                return view('frontend.settings.theme-details', [
                    'theme'       => $theme,
                    'userProfile' => $userProfile
                ]);
            }
        }
        
        abort(404);
    }
    
    /**
     * Update user cv theme
     * 
     * @param Request $request
     * @param type $id
     */
    public function install(Request $request) {
        if ($request->ajax() && $request->isMethod('post')) {
            
            $id = (int) $request->get('theme_id');
            
            if (Theme::find($id) !== null) {
                user()->userProfile->theme_id = $id;
                user()->userProfile->save();
                
                return pong(['message' => _t('saved')]);
            } 
            
            return pong(['message' => _t('oops')], _error(), 403);
        }
    }
    
    /**
     * Add new theme
     * 
     * @param Request $request
     * 
     * @return type
     */
    public function addNewTheme(Request $request) {
        if ($request->isMethod('POST')) {
            $rules     = $this->_getThemeRules();
            $messages  = $this->_getThemeMessages();
            $validator = Validator::make($request->all(), $rules, $messages);
            $file      = $request->file('__file');
            
            if ($validator->fails()) {
                return file_pong(['message' => $validator->errors()->first()], _error(), 403);
            }
            
            if ($file->isValid()) {
                $storagePath = config('frontend.themesTmpFolder');
                $fileStr     = random_string(16, $available_sets = 'lud');
                $fileExt     = $file->getClientOriginalExtension();
                $fileName    = $fileStr . '.' . $fileExt;
                
                try {
                    if ($file->move($storagePath, $fileName)) {
                        $error = $this->checkThemeFilesCorrect($storagePath . '/' . $fileStr, $storagePath . '/' . $fileName);
                        
                        if ($error !== true) {
                            
                            if (file_exists($storagePath . '/' . $fileStr)) {
                                File::deleteDirectory($storagePath . '/' . $fileStr);
                            }
                            
                            if(File::exists($storagePath . '/' . $fileName)) {
                                File::delete($storagePath . '/' . $fileName);
                            }
                            
                            return file_pong(['message' => $error], _error(), 403);
                        }
                        
                        return file_pong(['theme_path' => $storagePath . '/' . $fileStr]);
                    }
                } catch (Exception $ex) {
                    Log::error($ex->getMessage());
                }
                
            }
            
            return file_pong(['message' => _t('oops')], _error(), 403);
        }
    }
    
    /**
     * Check is the uploaded theme correct
     * 
     * @param string $folder
     * @param string $file
     * 
     * @return boolean
     */
    protected function checkThemeFilesCorrect($folder, $file) {
        $extAllow      = config('frontend.themeFileExtensionsAllow');
        $filesRequired = config('frontend.themeFilesRequired');
        $extUnallow    = [];
        $fileNames     = [];
        
        $zipArchive    = new \ZipArchive();
        $result        = $zipArchive->open($file);
        
        if ($result === TRUE) {
            $zipArchive->extractTo($folder);
            $zipArchive->close();
            
            $files = File::allFiles($folder);
            
            foreach ($files as $file) {
                $fileExt = File::extension($file);
                
                if ( ! in_array($fileExt, $extAllow)) {
                    $extUnallow[] = '.' . $fileExt;
                }
                
                $fileNames[] = File::name($file) . '.' . $fileExt;
            }
            
            if (count($extUnallow)) {
                $extUnallowStr = strtoupper(implode(', ', array_unique($extUnallow)));
                
                return _t('theme.upload.unallowExt', ['extUnallow' => $extUnallowStr]);
            }
            
            $fileMissing = array_diff($filesRequired, $fileNames);
            
            if (count($fileMissing)) {
                $fileMissingStr = strtoupper(implode(', ', $fileMissing));
                
                return _t('theme.upload.mising', ['fileMissing' => $fileMissingStr]);
            }
            
            return true;
            
        } else {
            return _t('theme.upload.unknow');
        }
    }

    /**
     * Get theme validation rules
     *
     * @return array
     */
    protected function _getThemeRules() {
        return [
            '__file' => 'required|mimes:zip|max:' . config('frontend.themeMaxFileSize')
        ];
    }

    /**
     * Get theme validation messages
     *
     * @return array
     */
    protected function _getThemeMessages() {
        return [
            '__file.required' => _t('no_file'),
            '__file.mimes'    => _t('file_compress_mimes'),
            '__file.max'      => _t('theme_max', ['fileSize' => config('frontend.themeMaxFileSizeMessage')]),
        ];
    }
    
    /**
     * Get save info result
     * 
     * @param array|object $save
     * 
     * @return array
     */
    protected function _saveInfoResult($save) {
        if ($save instanceof EmploymentHistory) {
                
            if (empty($save->company_website)) {
                $websiteText = '';
            } elseif (str_contains($save->company_website, 'https')) {
                $websiteText = str_replace('https://', '', $save->company_website);
            }else if (str_contains($save->company_website, 'http')) {
                $websiteText = str_replace('http://', '', $save->company_website);
            }

            $workedDate = ($save->is_current) ? $save->start_date->format('m/Y') . ' - ' . _t('setting.employment.current') : $save->start_date->format('m/Y') . ' - ' . $save->end_date->format('m/Y');

            return [
                'message' => _t('good_job'), 
                'data'    => [
                    'id'           => $save->id,
                    'name'         => $save->company_name,
                    'position'     => $save->position,
                    'achievement'  => $save->achievement,
                    'date'         => $workedDate,
                    'website_text' => $websiteText,
                    'website_href' => $save->company_website
            ]];
        } elseif($save instanceof Education) {

            return [
                'message' => _t('good_job'), 
                'data'    => [
                    'id'            => $save->id,
                    'name'          => $save->college_name,
                    'subject'       => $save->subject,
                    'date'          => $save->start_date->format('m/Y') . ' - ' . $save->end_date->format('m/Y'),
                    'qualification' => $save->qualification->name,
                    'achievements'  => $save->achievements
            ]];
        } elseif($save instanceof UserSkill) {
            return [
                'message' => _t('good_job'), 
                'data'    => [
                    'id'    => $save->id,
                    'name'  => $save->skill->name,
                    'votes' => $save->votes
            ]];
        } elseif($save instanceof UserProfile) {
            return [
                'message' => _t('good_job'), 
                'data'    => [
                    'socials'   => social_profile_list(),
                    'expertise' => ($save->expertise) ? $save->expertise->name : _t('setting.profile.pickexpertise'),
                    'cv_url'    => route('front_cv', ['slug' => $save->slug])
            ]];
        } elseif($save instanceof Theme) {
            return [
                'message' => _t('good_job'), 
                'data'    => [
                    'id'          => $save->id,
                    'name'        => $save->name,
                    'screenshot'  => asset("uploads/themes/{$save->slug}/screenshot.png"),
                    'url_details' => route('front_theme_details', ['theme_id' => $save->id]),
                    'desc'        => $save->description,
                    'devices'     => $save->devices(),
            ]];
        } elseif (true === $save) {
            return ['message' => _t('good_job')];
        } elseif(false !== $save) {
           return ['message' => $save->errors()->first(), _error(), 'code' => 403];
        } else {
            return ['message' => _t('good_job')];
        }
    }
    
    /**
     * Convert array of object to normal array [0 => '...', 1 => '...']
     * 
     * @param array  $places   List of cities or districts or wards
     * 
     * @return aray
     */
    protected function _placeObjectToArray($places) {
        
        $placesArr = [];
        
        if (count($places)) {
            foreach ($places as $place) {
                if (is_object($place)) {
                    $placesArr[( ! $place->id) ? '' : $place->id] = $place->name;
                }
            }
        }

        return $placesArr;
    }

    /**
     * Get cities by country id.
     * 
     * @param int  $countryId Country id
     * @param bool $toArray   Convert to array or not
     * 
     * @return array|\stdClass
     */
    protected function _getCityByCountryId($countryId = 0, $toArray = false) {
        
        $country = Country::find($countryId);
        $default = $this->_getDefaultAddress('city');
        $cities  = DB::table('cities')->select('id', 'name')
                                      ->where('country_id', $countryId)
                                      ->orderBy('type')
                                      ->orderBy('name')
                                      ->get();
                              
        if ( ! is_null($country) && 'VN' === $country->country_code) {
            $smallCities = collect($cities)->splice(5);
            $bigCities   = DB::table('cities')->select('id', 'name')->where('country_id', $countryId)->skip(0)->take(5)->get();
            $cities      = collect($bigCities)->merge($smallCities)->toArray();
        }
        
        if (count($cities)) {
            array_unshift($cities, $default);
        } else {
            $cities = [$default];
        }
        
        return ($toArray) ? $this->_placeObjectToArray($cities) : $cities;
    }
    
    /**
     * Get districts by city id.
     * 
     * @param int $cityId City id
     * @param bool $toArray   Convert to array or not
     * 
     * @return array|\stdClass
     */
    protected function _getDistrictByCityId($cityId = 0, $toArray = false) {
        
        $default   = $this->_getDefaultAddress('district');
        $districts = DB::table('districts')->select('id', DB::raw('CONCAT(type, " ", name) AS name'))
                                           ->where('city_id', $cityId)
                                           ->orderBy('name')
                                           ->get()->toArray();

        if (count($districts)) {
            array_unshift($districts, $default);
        } else {
            $districts = [$default];
        }
        
        return ($toArray) ? $this->_placeObjectToArray($districts) : $districts;
    }
    
    /**
     * Get Wards by district id.
     * 
     * @param int $districtId District id
     * 
     * @return array|\stdClass
     */
    protected function _getWardByDistrictId($districtId = 0, $toArray = false) {
        
        $default = $this->_getDefaultAddress('ward');
        $wards   = DB::table('wards')->select('id', DB::raw('CONCAT(type, " ", name) AS name'))
                                     ->where('district_id', $districtId)
                                     ->orderBy('name')
                                     ->get()->toArray();
                             
        if (count($wards)) {
            array_unshift($wards, $default);
        } else {
            $wards = [$default];
        }
        
        return ($toArray) ? $this->_placeObjectToArray($wards) : $wards;
    }
    
    /**
     * Get default text for selection address.
     * 
     * @param string $type address type
     * 
     * @return \stdClass
     */
    protected function _getDefaultAddress($type) {
        $default     = new \stdClass();
        $default->id = 0;
        
        switch ($type) {
            case 'city':
                $default->name = _t('setting.profile.city');
                break;
            case 'district':
                $default->name = _t('setting.profile.district');
                break;
            case 'ward':
                $default->name = _t('setting.profile.ward');
                break;
            default:
                break;
        }
        
        return $default;
    }

    /**
     * Upload cover image then duplicate to many sizes
     * 
     * @param UploadFile $file
     * 
     * return array
     */
    protected function _uploadCoverImage($file) {
        try {
            $storagePath       = config('frontend.coversFolder');
            $sizes             = config('frontend.coverSizes');
            $names['original'] = generate_filename($storagePath, $file->getClientOriginalExtension(), [
                'prefix' => 'cover_', 
                'suffix' => "_{$sizes['original']}"
            ]);
                
            unset($sizes['original']);

            if ($file->move($storagePath, $names['original'])) {
                foreach ($sizes as $size) {
                    $name = generate_filename($storagePath, $file->getClientOriginalExtension(), [
                        'prefix' => 'cover_', 
                        'suffix' => "_{$size['w']}x{$size['h']}"
                    ]);
                        
                    $image = ImageIntervention::make($storagePath . '/' . $names['original'])->orientate();
                    $image->fit($size['w'], $size['h'], function ($constraint) {
                        $constraint->upsize();
                    });

                    $image->save($storagePath . '/' . $name);
                    $names[$size['w']] = $name;
                }
            }
            
            return $names;
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Upload cover image then duplicate to many sizes
     * 
     * @param UploadFile $file
     * 
     * return array
     */
    protected function _uploadAvatarImage($file) {    
        try {
            $storagePath       = config('frontend.avatarsFolder');
            $sizes             = config('frontend.avatarSizes');
            $names['original'] = generate_filename($storagePath, $file->getClientOriginalExtension(), [
                'prefix' => 'avatar_', 
                'suffix' => "_{$sizes['original']}"
            ]);
                
            unset($sizes['original']);

            if ($file->move($storagePath, $names['original'])) {
                foreach ($sizes as $size) {
                    $name = generate_filename($storagePath, $file->getClientOriginalExtension(), [
                        'prefix' => 'avatar_', 
                        'suffix' => "_{$size['w']}x{$size['h']}"
                    ]);
                        
                    $image = ImageIntervention::make($storagePath . '/' . $names['original'])->orientate();
                    $image->fit($size['w'], $size['h'], function ($constraint) {
                        $constraint->upsize();
                    });

                    $image->save($storagePath . '/' . $name);
                    $names[$size['w']] = $name;
                }
            }
            
            return $names;
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    
    /**
     * Get avatar validation rules
     *
     * @return array
     */
    protected function _getAvatarRules() {
        return [
            '__file' => 'required|image|mimes:jpg,png,jpeg,gif|max:' . config('frontend.avatarMaxFileSize')
        ];
    }

    /**
     * Get avatar validation messages
     *
     * @return array
     */
    protected function _getAvatarMessages() {
        return [
            '__file.required' => _t('no_file'),
            '__file.image'    => _t('file_not_image'),
            '__file.mimes'    => _t('file_image_mimes'),
            '__file.max'      => _t('avatar_max', ['fileSize' => config('frontend.avatarMaxFileSizeMessage')]),
        ];
    }
    
    /**
     * Get cover validation rules
     *
     * @return array
     */
    protected function _getCoverRules() {
        return [
            '__file' => 'required|image|mimes:jpg,png,jpeg,gif|max:' . config('frontend.coverMaxFileSize')
        ];
    }

    /**
     * Get cover validation messages
     *
     * @return array
     */
    protected function _getCoverMessages() {
        return [
            '__file.required' => _t('no_file'),
            '__file.image'    => _t('file_not_image'),
            '__file.mimes'    => _t('file_image_mimes'),
            '__file.max'      => _t('avatar_max', ['fileSize' => config('frontend.coverMaxFileSizeMessage')]),
        ];
    }
}