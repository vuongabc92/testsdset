<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Hash;
use Route;
use File;
use App\Models\EmploymentHistory;
use App\Models\Education;
use App\Models\Skill;
use App\Models\UserSkill;
use App\Models\Theme;

trait SaveSettings {
    
    /**
     * Save settings email.
     * 
     * @param Request $request
     * 
     * @return boolean|JSON
     */
    public function saveEmail(Request $request) {
        $validator = validator($request->all(), $this->_saveEmailValidateRules(), $this->_saveEmailValidateMessages());
        $validator->after(function($validator) use($request) {
            if ( ! Hash::check($request->get('password'), user()->password)) {
                $validator->errors()->add('password', _t('auth.login.pass_wrong'));
            }
        });
        
        if ($validator->fails()) {
            return $validator;
        }
        
        user()->email = $request->get('email');
        user()->save();
        
        return true;
    }
    
    /**
     * Save settings slug.
     * 
     * @param Request $request
     * 
     * @return boolean|JSON
     */
    public function saveSlug(Request $request) {
        
        $userProfile = user()->userProfile;
        $slug        = $request->get('slug');
        $validator   = validator($request->all(), $this->_saveSlugValidateRules(), $this->_saveSlugValidateMessages());
        
//        $validator->after(function($validator) use($slug) {
//            if (getSlugRemainDay()) {
//                $remainDay     = getSlugRemainDay();
//                $slugUpdatedAt = new \DateTime(user()->userProfile->slug_updated_at);
//                $slugUpdatedAt->modify("+{$remainDay} days");
//                
//                $validator->errors()->add('slug', _t('setting.profile.slug_time', ['date' => $slugUpdatedAt->format('d/m/Y')]));
//            }
//            
//            $privateUrls = config('frontend.unavailableCVUrls') + $this->_getPrivateUrls();
//            
//            if (in_array($slug, $privateUrls)) {
//                $validator->errors()->add('slug', _t('setting.profile.slug_exi'));
//            }
//        });
        
        if ($validator->fails()) {
            return $validator;
        }
        
        $userProfile->slug            = $slug;
        $userProfile->slug_updated_at = new \DateTime();
        $userProfile->save();
        
        return $userProfile;
    }
    
    /**
     * Save settings password.
     * 
     * @param Request $request
     * 
     * @return boolean|JSON
     */
    public function savePassword(Request $request) {
        
        $rules = $this->_savePasswordValidateRules();
        
        if (user()->password === null) {
            $rules = remove_rules($rules, 'old_password');
        }
        
        $validator = validator($request->all(), $rules, $this->_savePasswordValidateMessages());
        
        if (user()->password !== null) {
            $validator->after(function($validator) use($request) {
                if ( ! Hash::check($request->get('old_password'), user()->password)) {
                    $validator->errors()->add('old_password', _t('auth.login.pass_wrong'));
                }
            });
        }
        
        if ($validator->fails()) {
            return $validator;
        }
        
        user()->password = bcrypt($request->get('new_password'));
        user()->save();
        
        return true;
    }
    
    /**
     * Save settings personal info.
     * 
     * @param Request $request
     * 
     * @return boolean|JSON
     */
    public function savePersonalInfo(Request $request) {
        
        $validator = validator($request->all(), $this->_savePersonalInfoRules(), $this->_savePersonalInfoMessages());
        if ($validator->fails()) {
            return $validator;
        }
        
        $d                              = (int) $request->get('date');
        $m                              = (int) $request->get('month');
        $y                              = (int) $request->get('year');
        $gender                         = (int) $request->get('gender');
        $maritalStatus                  = (int) $request->get('marital_status');
        
        $userProfile                    = user()->userProfile;
        $userProfile->day_of_birth      = ($d && $m && $y) ? new \DateTime("{$m}/{$d}/{$y}") : null;
        $userProfile->gender_id         = ($gender) ? $gender : null;
        $userProfile->marital_status_id = ($maritalStatus) ? $maritalStatus : null;
        $userProfile->first_name        = $request->get('first_name');
        $userProfile->last_name         = $request->get('last_name');
        $userProfile->about_me          = $request->get('about_me');
        $userProfile->hobbies           = $request->get('hobbies');
        $userProfile->save();
        
        return true;
    }
    
    /**
     * Save settings expertise
     * 
     * @param Request $request
     * 
     * @return boolean|JSON
     */
    public function saveExpertise(Request $request) {
        $validator = validator($request->all(), $this->_saveExpertiseRules(), $this->_saveExpertiseMessages());
        
        if ($validator->fails()) {
            return $validator;
        }
        
        $userProfile = user()->userProfile;
        $userProfile->expertise_id = ( ! empty($id = $request->get('expertise_id'))) ? $id : null;
        $userProfile->save();
        
        return $userProfile;
    }
    
    /**
     * Save settings contact.
     * 
     * @param Request $request
     * 
     * @return boolean|JSON
     */
    public function saveContactInfo(Request $request) {
        
        $userProfile = user()->userProfile;
        $website     = $request->get('website');
        
        if ($request->has('social_type') && $request->has('social_profile')) {
            return $this->_saveSocialLinks($request);
        }
        
        $validateRules = $this->_saveContactRules();
        
//        if (empty($request->get('city'))) {
//            $validateRules['city'] = 'required_with:country';
//        }
//        
//        if (empty($request->get('district'))) {
//            $validateRules['district'] = 'required_with:city';
//        }
//        
//        if (empty($request->get('ward'))) {
//            $validateRules['ward'] = 'required_with:district';
//        }
        
        $validator = validator($request->all(), $validateRules, $this->_saveContactMessages());
        if ($validator->fails()) {
            return $validator;
        }
        
        $userProfile->street_name  = $request->get('street_name');
        $userProfile->city_name    = $request->get('city_name');
        $userProfile->country_id   = (empty($request->get('country_id')))      ? null : $request->get('country_id');
//        $userProfile->city_id      = (empty($request->get('city')))         ? null : $request->get('city');
//        $userProfile->district_id  = (empty($request->get('district')))     ? null : $request->get('district');
//        $userProfile->ward_id      = (empty($request->get('ward')))         ? null : $request->get('ward');
        $userProfile->phone_number = (empty($request->get('phone_number'))) ? null : $request->get('phone_number');
        $userProfile->website      = (strpos($website, 'http') === false)   ? 'http://' . $website : $website;
        $userProfile->save();
        
        return true;
    }
    
    /**
     * Generate social links
     * 
     * @param Request $request
     * 
     * @return string
     */
    protected function _saveSocialLinks($request) {
        
        $userProfile = user()->userProfile;
        $type        = $request->get('social_type');
        $socialLink  = $request->get('social_profile');
        
        $validator = validator(
            $request->all(), 
            array('social_type' => 'required', 'social_profile' => 'required'), 
            array('social_type.required' => _t('settings.contact.social_req'), 'social_profile.required' => _t('settings.contact.sociallink_req'))
        );
            
        $validator->after(function($validator) use($type, $socialLink) {
            $socialAllowed = config('frontend.availableSocial');
            $socialUrls    = config('frontend.socialUrls');
            $match         = false;
            
            if ( ! isset($socialAllowed[$type])) {
                $validator->errors()->add('social_type', _t('settings.contact.social_exi'));
            }
            
            foreach ($socialUrls as $id => $url) {
                if (strpos($socialLink, $url) !== false) {
                    $match = $id;
                }
            }
            
            if ($match !== $type) {
                $validator->errors()->add('social_type', _t('settings.contact.social_linkwrog'));
            }
        });
        
        if ($validator->fails()) {
            return $validator;
        }
        
        $socialRaw      = $userProfile->social_network;
        $socials        = is_null($socialRaw) ? [] : unserialize($socialRaw);
        $url            = parse_url(trim((strpos($socialLink, 'http') === false) ? 'http://' . $socialLink : $socialLink));
        $host           = isset($url['host'])  ? $url['host']        : '';
        $path           = isset($url['path'])  ? $url['path']        : '';
        $query          = isset($url['query']) ? '?' . $url['query'] : '';
        $socials[$type] = $host . $path . $query;
        
        $userProfile->social_network = serialize($socials);
        $userProfile->save();
        
        return $userProfile;
    }
    
    /**
     * Save settings employment.
     * 
     * @param Request $request
     * 
     * @return boolean|JSON
     */
    public function saveEmployment(Request $request) {
       
        if ($request->has('employment_expected')) {
            $validator = validator($request->all(), ['expected_job' => 'required|max:250'], [
                'expected_job.required' => _t('setting.employment.expjob_req'),
                'expected_job.max'      => _t('setting.employment.expjob_max')
            ]);
            
            if ($validator->fails()) {
                return $validator;
            }
            user()->userProfile->expected_job = $request->get('expected_job');
            user()->userProfile->save();
            
            return true;
        }
        
        $validator  = validator($request->all(), $this->_saveEmploymentRules(), $this->_saveEmploymentMessages());
        $startMonth = $request->get('start_month');
        $startYear  = $request->get('start_year');
        $endMonth   = ( ! empty($request->get('end_month'))) ? $request->get('end_month')              : 0;
        $endYear    = ( ! empty($request->get('end_year')))  ? $request->get('end_year')               : 0;
        $current    = ($request->has('current_company'))     ? (bool) $request->get('current_company') : false;
        $website    = $request->get('website');
        $startDate  = ($startMonth && $startYear) ? new \DateTime("{$startMonth}/{$startMonth}/{$startYear}") : null;
        $endDate    = ( ! $endMonth || ! $endYear || $current) ? $startDate : new \DateTime("{$endMonth}/{$endMonth}/{$endYear}");
        
        $validator->after(function($validator) use($startDate, $endDate) {
            if ($startDate > $endDate) {
                $validator->errors()->add('start_month', _t('setting.employment.datecompare'));
            }
        });
        
        if ($validator->fails()) {
            return $validator;
        }
        
        if ($current) {
            EmploymentHistory::all()->each(function($item, $key){
                if ($item->is_current) {
                    $item->is_current = false;
                    $item->end_date   = $item->start_date;
                }
                
                $item->save();
            });
        }
        
        if ($request->has('id') && EmploymentHistory::find($request->get('id'))) {
            $employmentHistory = EmploymentHistory::find($request->get('id'));
        } else {
            $employmentHistory = new EmploymentHistory();
        }
        
        $employmentHistory->user_id         = user_id();
        $employmentHistory->company_name    = $request->get('company_name');
        $employmentHistory->position        = $request->get('position');
        $employmentHistory->start_date      = $startDate;
        $employmentHistory->end_date        = $endDate;
        $employmentHistory->is_current      = $current;
        $employmentHistory->company_website = trim((strpos($website, 'http') === false) ? 'http://' . $website : $website);
        $employmentHistory->achievement     = $request->get('achievement');
        $employmentHistory->save();
        
        return $employmentHistory;
    }
    
    /**
     * Save settings education.
     * 
     * @param Request $request
     * 
     * @return boolean|JSON
     */
    public function saveEducation(Request $request) {
        
        $validator  = validator($request->all(), $this->_saveEducationRules(), $this->_saveEducationMessages());
        $startMonth = $request->get('start_month');
        $startYear  = $request->get('start_year');
        $endMonth   = $request->get('end_month');
        $endYear    = $request->get('end_year');
        $startDate  = new \DateTime("{$startMonth}/{$startMonth}/{$startYear}");
        $endDate    = new \DateTime("{$endMonth}/{$endMonth}/{$endYear}");
        
        $validator->after(function($validator) use($startDate, $endDate) {
            if ($startDate > $endDate) {
                $validator->errors()->add('start_month', _t('setting.education.datecompare'));
            }
        });
        
        if ($validator->fails()) {
            return $validator;
        }
        
        if ($request->has('id') && Education::find($request->get('id'))) {
            $education = Education::find($request->get('id'));
        } else {
            $education = new Education();
        }
        
        $education->user_id          = user_id();
        $education->college_name     = $request->get('college_name');
        $education->subject          = $request->get('subject');
        $education->start_date       = new \DateTime("{$startMonth}/{$startMonth}/{$startYear}");
        $education->end_date         = new \DateTime("{$endMonth}/{$endMonth}/{$endYear}");
        $education->qualification_id = $request->get('qualification');
        $education->save();
        
        return $education;
    }
    
    /**
     * Save settings skill
     * 
     * @param Request $request
     * 
     * @return boolean|UserSkill
     */
    public function saveSkill(Request $request) {
        
        $rating = $request->has('id') && $request->has('votes');
        $rules  = $this->_saveSkillRules();
        
        if ($rating) {
            $rules = remove_rules($rules, 'skill');
        }
        
        $validator = validator($request->all(), $rules, $this->_saveSkillMessages());
        
        if ($validator->fails()) {
            return $validator;
        }
        
        if ($request->has('id') && $request->has('votes')) {
            
            $userSkill = UserSkill::find($request->get('id'));
            if (null !== $userSkill) {
                $userSkill->votes = $request->get('votes');
                $userSkill->save();
                
                return $userSkill;
            }
            
            return false;
        } elseif ($request->has('skill')) {
            $skill = Skill::where('name', $request->get('skill'))->first();
            if (null === $skill) {
                $skill       = new Skill();
                $skill->name = $request->get('skill');
                $skill->save();
            }
            
            $userSkill           = new UserSkill();
            $userSkill->user_id  = user()->id;
            $userSkill->skill_id = $skill->id;
            $userSkill->save();
            
            return $userSkill;
        }
        
        return false;
    }
    
    public function saveTheme($request) {
        $rules       = $this->saveThemeValidateRules();
        $messages    = $this->saveThemeValidateMessages();
        $themePath   = $request->get('theme_path');
        $themeFolder = config('frontend.themesFolder');
        $themeName   = $request->get('theme_name');
        $themeSlug   = str_slug($themeName, '-');
        $devices     = $request->get('devices');
        $expertises  = array_filter($request->get('expertise_id'));
        $formData    = $request->all();
        
        
        if( ! count($expertises)) {
            $rules = remove_rules($rules, 'expertise_id');
        }
        
        $formData['expertise_id'] = $expertises;
        $validator                = validator($formData, $rules, $messages);
        
        $validator->after(function($validator) use($themePath, $themeFolder, $themeSlug, $devices) {
            if ( ! File::exists($themePath)) {
                $validator->errors()->add('theme_path', _t('theme.validate.themezipgone'));
            }
            
            if (File::exists($themeFolder . '/' . $themeSlug)) {
                $validator->errors()->add('theme_path', _t('theme.validate.themeslugexisted'));
            }
            
            if (is_array($devices) && count($devices)) {
                foreach($devices as $device) {
                    if( ! in_array($device, ['desktop', 'tablet', 'mobile'])) {
                        $validator->errors()->add('theme_path', _t('theme.validate.devicesin'));
                    }
                }
            }
        });
        
        if ($validator->fails()) {
            return $validator;
        }
        
        try {
            $files     = File::allFiles($themePath);
            $indexPath = '';
            
            foreach ($files as $file) {
                $fileName = File::name($file) . '.' . File::extension($file);
                
                if ($fileName === 'index.html') {
                    $indexPath = $file;
                }
            }
            
            if ( File::exists($indexPath) ) {
                $parentDir = File::dirname($indexPath);
                
                File::moveDirectory($parentDir, $themePath . '/' . $themeSlug);
                File::moveDirectory($themePath . '/' . $themeSlug, $themeFolder . '/' . $themeSlug);
            }
            
            File::deleteDirectory($themePath);
        
            if(File::exists($themePath . '.zip')) {
                File::delete($themePath . '.zip');
            }
            
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            
            $validator->errors()->add('theme_path', _t('theme.validate.moveziperror'));
            
            return $validator;
        }
        
        $theme = new Theme();
        $theme->user_id     = user_id();
        $theme->name        = $themeName;
        $theme->slug        = $themeSlug;
        $theme->version     = $request->get('theme_version');
        $theme->description = $request->get('theme_desc');
        $theme->devices     = (is_array($devices)) ? serialize($devices) : '';
        $theme->expertises  = (is_array($expertises)) ? serialize($expertises) : '';
        $theme->tags        = $request->get('theme_tags');
        $theme->save();
        
        return $theme;
    }

    /**
     * Get site urls that user CV slug must not be duplicated
     * 
     * @return array
     */
    protected function _getPrivateUrls() {
                
        $routeCollection = Route::getRoutes();
        $routes          = [];
        
        if (count($routeCollection)) {
            foreach ($routeCollection as $route) {
                $routeSplit = explode('/', $route->getPath());
                if (count($routeSplit) === 1) {
                    $routes[] = $route->getPath();
                }
            }
        }
        
        return array_unique($routes);
    }

    /**
     * Save theme validate rules.
     * 
     * @return array
     */
    public function saveThemeValidateRules() {
        return [
            'theme_path'    => 'required',
            'theme_name'    => 'required|alpha_spaces|min:3|max:250|unique:themes,name',
            'theme_version' => 'required|min:2|max:10',
            'theme_desc'    => 'required|min:20',
            'expertise_id'  => 'exists:expertises,id'
        ];
    }
    
    public function saveThemeValidateMessages() {
        return [
            'theme_path.required'     => _t('theme.validate.themezipreq'),
            'theme_name.required'     => _t('theme.validate.themenamereq'),
            'theme_name.alpha_spaces' => _t('theme.validate.themenamealdash'),
            'theme_name.min'          => _t('theme.validate.themenamemin'),
            'theme_name.max'          => _t('theme.validate.themenamemax'),
            'theme_name.unique'       => _t('theme.validate.themenameuni'),
            'theme_version.required'  => _t('theme.validate.themeverreq'),
            'theme_version.min'       => _t('theme.validate.themevermin'),
            'theme_version.max'       => _t('theme.validate.themevermax'),
            'theme_desc.required'     => _t('theme.validate.themedescreq'),
            'theme_desc.min'          => _t('theme.validate.themedescmin'),
            'expertise_id.exists'     => _t('theme.validate.expertise_idexi'),
        ];  
    }

    /**
     * Save email validate rules.
     * 
     * @return array
     */
    protected function _saveEmailValidateRules() {
        return [
            'email'    => 'required|email|max:128|unique:users,email,' . user_id() . ',id', 
            'password' => 'required'
        ];
    }
    
    public function _saveEmailValidateMessages() {
        return [
            'email.required'    => _t('register.email.req'),
            'email.email'       => _t('register.email.email'),
            'email.max'         => _t('register.email.max'),
            'email.unique'      => _t('register.email.uni'),
            'password.required' => _t('register.pass.req'),
        ];
    }
    
    /**
     * Save slug validate rules.
     * 
     * @return array
     */
    protected function _saveSlugValidateRules() {
        return ['slug' => 'required|alpha_dash|min:2|max:128|unique:user_profile,slug,' . user_id() . ',user_id'];
    }
    
    /**
     * Save slug validate messages.
     * 
     * @return array
     */
    protected function _saveSlugValidateMessages() {
        return [
            'slug.required'   => _t('setting.profile.slug_req'),
            'slug.alpha_dash' => _t('setting.profile.slug_alp'),
            'slug.min'        => _t('setting.profile.slug_min'),
            'slug.max'        => _t('setting.profile.slug_max'),
            'slug.unique'     => _t('setting.profile.slug_uni'),
        ];
    }
    
    /**
     * Save slug validate rules.
     * 
     * @return array
     */
    protected function _savePasswordValidateRules() {
        return [
            'old_password' => 'required',
            'new_password' => 'required|min:6|max:60|confirmed',
        ];
    }
    
    /**
     * Save slug validate messages.
     * 
     * @return array
     */
    protected function _savePasswordValidateMessages() {
        return [
            'old_password.required'              => _t('setting.profile.oldpass_req'),
            'old_password.min'                   => _t('setting.profile.oldpass_min'),
            'old_password.max'                   => _t('setting.profile.oldpass_max'),
            'new_password.required'              => _t('setting.profile.newpass_req'),
            'new_password.min'                   => _t('setting.profile.newpass_min'),
            'new_password.max'                   => _t('setting.profile.newpass_max'),
            'new_password.confirmed'             => _t('setting.profile.newpass_con'),
            'new_password_confirmation.required' => _t('setting.profile.renewpass_req'),
            'new_password_confirmation.min'      => _t('setting.profile.renewpass_min'),
            'new_password_confirmation.max'      => _t('setting.profile.renewpass_max'),
        ];
    }
    
    /**
     * Save personal validate rules.
     * 
     * @return array
     */
    protected function _savePersonalInfoRules() {
        return [
            'first_name'     => 'required_with:last_name|max:32',
            'last_name'      => 'required_with:first_name|max:32',
            'date'           => 'required_with:month,year',
            'month'          => 'required_with:date,year',
            'year'           => 'required_with:date,month',
            'marital_status' => 'exists:marital_statuses,id',
            'gender'         => 'exists:genders,id',
            'about_me'       => 'max:500',
            'hobbies'        => 'max:250'
        ];
    }
    
    /**
     * Save personal validate messages.
     * 
     * @return array
     */
    protected function _savePersonalInfoMessages() {
        return [
            'first_name.required_with' => _t('setting.profile.fname_req'),
            'first_name.max'           => _t('setting.profile.fname_max'),
            'last_name.required_with'  => _t('setting.profile.lname_req'),
            'last_name.max'            => _t('setting.profile.lname_max'),
            'date.required_with'       => _t('setting.profile.date_req'),
            'month.required_with'      => _t('setting.profile.month_req'),
            'year.required_with'       => _t('setting.profile.year_req'),
            'marital_status.exists'    => _t('setting.profile.marital_exi'),
            'gender.exists'            => _t('setting.profile.gender_exi'),
            'about_me.max'             => _t('setting.profile.aboutme_max'),
            'hobbies.max'              => _t('setting.profile.hobbies_max'),
        ];
    }
    
    /**
     * Save contact validate rules.
     * 
     * @return array
     */
    protected function _saveExpertiseRules() {
        return [
            'expertise_id' => 'exists:expertises,id'
        ];
    }
    
    /**
     * Save personal validate messages.
     * 
     * @return array
     */
    protected function _saveExpertiseMessages() {
        return [
            'expertise_id.exists' => _t('setting.profile.expertise_exi')
        ];
    }
    
    /**
     * Save contact validate rules.
     * 
     * @return array
     */
    protected function _saveContactRules() {
        return [
            'country_id'   => 'required_with:city|exists:countries,id',
            'street_name'  => 'max:250',
            'city_name'    => 'max:250',
//            'country'      => 'required_with:city|exists:countries,id',
//            'city'         => 'required_with:district|exists:cities,id',
//            'district'     => 'required_with:ward|exists:districts,id',
//            'ward'         => 'exists:wards,id',
            'phone_number' => 'max:32',
            'website'      => 'max:250',
        ];
    }
    
    /**
     * Save personal validate messages.
     * 
     * @return array
     */
    protected function _saveContactMessages() {
        return [
            'street_name.max'        => _t('setting.profile.sname_max'),
            'city_name.max'          => _t('setting.profile.cname_max'),
//            'country.required_with'  => _t('setting.profile.country_rwith'),
            'country_id.exists'         => _t('setting.profile.country_exi'),
//            'city.required_with'     => _t('setting.profile.city_rwith'),
//            'city.exists'            => _t('setting.profile.city_exi') ,
//            'district.required_with' => _t('setting.profile.district_rwith'),
//            'district.exists'        => _t('setting.profile.district_exi'),
//            'ward.exists'            => _t('setting.profile.ward_exi'),
            'phone.max'              => _t('setting.profile.phone_max'),
            'website.max'            => _t('setting.profile.website_max')
        ];
    }
    
    /**
     * Save employment validate rules
     * 
     * @return array
     */
    protected function _saveEmploymentRules() {
        return [
            'company_name' => 'required|max:250',
            'position'     => 'required|max:250',
            'start_month'  => 'required',
            'start_year'   => 'required',
            'end_month'    => 'required_without:current_company',
            'end_year'     => 'required_without:current_company',
            'end_year'     => 'required_without:current_company',
            'achievement'  => 'max:500'
        ];
    }
    
    /**
     * Save employment validate messages
     * 
     * @return array
     */
    protected function _saveEmploymentMessages() {
        return [
            'company_name.required'      => _t('setting.employment.comname_req'),
            'company_name.max'           => _t('setting.employment.comname_max'),
            'position.required'          => _t('setting.employment.position_req'),
            'position.max'               => _t('setting.employment.position_max'),
            'start_month.required'       => _t('setting.employment.startmonth_req'),
            'start_year.required'        => _t('setting.employment.startyear_req'),
            'end_month.required_without' => _t('setting.employment.endmonth_req'),
            'end_year.required_without'  => _t('setting.employment.endyear_req'),
            'achievement.max'            => _t('setting.employment.achieve_max')
        ];
    }
    
    /**
     * Save employment validate rules
     * 
     * @return array
     */
    protected function _saveEducationRules() {
        return [
            'college_name'  => 'required|max:250',
            'subject'       => 'required|max:250',
            'start_month'   => 'required',
            'start_year'    => 'required',
            'end_month'     => 'required',
            'end_year'      => 'required',
            'qualification' => 'required|exists:qualification,id',
        ];
    }
    
    /**
     * Save employment valdiate messages
     * 
     * @return array
     */
    protected function _saveEducationMessages() {
        return [
            'college_name.required'  => _t('setting.education.colname_req'),
            'college_name.max'       => _t('setting.education.colname_max'),
            'subject.required'       => _t('setting.education.subject_req'),
            'subject.max'            => _t('setting.education.subject_max'),
            'start_month.required'   => _t('setting.education.startmonth_req'),
            'start_year.required'    => _t('setting.education.startyear_req'),
            'end_month.required'     => _t('setting.education.endmonth_req'),
            'end_year.required'      => _t('setting.education.endyear_req'),
            'qualification.required' => _t('setting.education.qualif_req'),
            'qualification.exists'   => _t('setting.education.qualif_exi'),
        ];
    }
    
    /**
     * Save skill validate rules
     * 
     * @return array
     */
    protected function _saveSkillRules() {
        return [
            'skill' => 'required|max:250',
            'id'    => 'exists:user_skills,id',
            'votes' => 'between:1,5'
        ];
    }
    
    /**
     * Save skill validate messages
     * 
     * @return array
     */
    protected function _saveSkillMessages() {
        return [
            'skill.required' => _t('setting.skill.req'),
            'skill.max'      => _t('setting.skill.max'),
            'id.exists'      => _t('setting.skill.exi'),
            'votes.between'  => _t('setting.skill.bet')
        ]; 
    }
}