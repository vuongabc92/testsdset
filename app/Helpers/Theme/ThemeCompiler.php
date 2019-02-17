<?php
namespace App\Helpers\Theme;

use Carbon\Carbon;
use App\Helpers\Theme\Config;

class ThemeCompiler extends Compiler {
    
    /**
     * The path currently being compiled.
     *
     * @var string
     */
    protected $path;

    /**
     * The file name currently being compiled.
     *  
     * @var string 
     */
    protected $filename = 'index.html';
    
    /**
     * The downdload file name.
     *  
     * @var string 
     */
    protected $download = 'download.html';

    /**
     * The downdload file name.
     *
     * @var string
     */
    protected $downloadCss = 'css/download.css';

    /**
     * Folder name that container all themes
     *  
     * @var string 
     */
    protected $themesFolder = 'themes';

    /**
     * Folder name that container all themes
     *
     * @var string
     */
    protected $commonThemeAssetsFolder = 'common-theme-assets';

    /**
     * Public folder
     * 
     * @var string 
     */
    protected $publicFolder = 'public';

    /**
     * Array of opening and closing tags for function.
     *
     * @var array
     */
    protected $contentTags = ['{{', '}}'];
    
    /**
     * Array of opening and closing tags for regular echos.
     *
     * @var array
     */
    protected $functionTags = ['{%', '%}'];
    
    /**
     * Array of opening and closing tags for regular echos.
     *
     * @var array
     */
    protected $foreachPatterm = '/foreach(.*)/';
    
    /**
     * Array of opening and closing tags config regular echos.
     *
     * @var array
     */
    protected $configPatterm = '/config/';
    
    /**
     * Experience variables name
     * 
     * @var string 
     */
    protected $experienceVariable = 'experiences';
    
    /**
     * Education variables name
     * 
     * @var string 
     */
    protected $educationVariable = 'education';
    
    /**
     * Skill variables name
     * 
     * @var string 
     */
    protected $skillVariable = 'skills';
    
    /**
     * Social variables name
     * 
     * @var string 
     */
    protected $socialVariable = 'socials';
    
    /**
     * Hobbies variables name
     * 
     * @var string 
     */
    protected $hobbyVariable = 'hobbies';
    
    /**
     * Experience available properties
     * 
     * @var array 
     */
    protected $experienceProperties = [
        'TIME',
        'TIME_LETTER',
        'COMPANY_NAME',
        'POSITION',
        'DESCRIPTION',
        'LINK',
        'LINK_TEXT'
    ];
    
    /**
     * Experience available properties
     * 
     * @var array 
     */
    protected $educationProperties = [
        'TIME',
        'TIME_LETTER',
        'COLLEGE_NAME',
        'SUBJECT',
        'QUALIFICATION'
    ];
    
    /**
     * Skill available properties
     * 
     * @var array 
     */
    protected $skillProperties = [
        'NAME',
        'RATES',
        'PERCENT'
    ];
    
    /**
     * Social available properties
     * 
     * @var array 
     */
    protected $socialProperties = [
        'LINK',
        'ICON',
        'TEXT'
    ];
    
    /**
     * Hobby available properties
     * 
     * @var array 
     */
    protected $hobbyProperties = [
        'HOBBY',
    ];

    /**
     * Array of variables
     *
     * @var array 
     */
    protected $variables = [
        'ASSETS',
        'AVATAR',
        'AVATAR_128',
        'AVATAR_256',
        'AVATAR_512',
        'AGE',
        'ABOUT_ME',
        'BIRTHDAY',
        'CITY',
        'COMMON_ASSETS',
        'COUNTRY',
        'COUNTRY_CODE',
        'CITY_TYPE',
        'COVER',
        'COVER_768',
        'COVER_960',
        'COVER_1200',
        'DATE_OF_BIRTH',
        'DISTRICT',
        'DISTRICT_TYPE',
        'EMAIL',
        'EXPECTED_JOB',
        'LINK_STYLESHEET_DOWNLOAD',
        'FIRST_NAME',
        'FIRST_NAME_LETTER',
        'FULL_NAME',
        'GENDER',
        'HOBBIES',
        'LAST_NAME',
        'LAST_NAME_LETTER',
        'MONTH_OF_BIRTH',
        'MARITAL_STATUS',
        'PDF_WRAPPER_CLASS',
        'PHONE_NUMBER',
        'STREET',
        'WARD',
        'WARD_TYPE',
        'WEBSITE_NAME',
        'WEBSITE_LINK',
        'YEAR_OF_BIRTH',
    ];
    
    /**
     * Theme configuration
     *
     * @var array 
     */
    protected $configuration;
    protected $configPdf;

    /**
     * Compile the view at the given path.
     *
     * @param bool $isDownload Compile for download or preview.
     * @return bool|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function compile($isDownload = false) {
        
        $file = $this->generateFilenamePath($isDownload);
        
        if ( ! check_file($file)) {
            return false;
        }
        
        $contents = $this->compileString($this->files->get($file));
        
        return $this->compileFunctions($contents);
    }

    /**
     * Compile string variables
     *
     * @param string $contents
     *
     * @return string
     */
    protected function compileString($contents) {
        $pattern  = sprintf('/%s\s*(.+?)\s*%s(\r?\n)?/s', $this->contentTags[0], $this->contentTags[1]);
        $callback = function($matches) {

            if (in_array($matches[1], $this->variables)) {
                $methodSplit = explode('_', $matches[1]);
                $method      = 'compile';


                foreach ($methodSplit as $item) {
                    $item    = strtolower($item);
                    $method .= ucfirst($item);
                }

                if (method_exists($this, $method)) {
                    return ($this->$method() !== false && trim($this->$method())) === '' ? $matches[1] : $this->$method();
                }
            }

            return $matches[0];
        };

        return preg_replace_callback($pattern, $callback, $contents);
    }

    /**
     * Compile statement in theme such as: foreach, ...
     *
     * @param string $contents
     *
     * @return string
     */
    protected function compileFunctions($contents) {
        $pattern  = sprintf('/%s(.*?)%s(.*?)%s(.*?)%s/s', $this->functionTags[0], $this->functionTags[1], $this->functionTags[0], $this->functionTags[1]);
        $callback = function($matches) {

            $method = 'compile';
            if (preg_match($this->foreachPatterm, trim($matches[1]))) {
                $method .= 'Foreach';
            }

            if (preg_match($this->configPatterm, trim($matches[1]))) {
                $method .= 'Config';
            }

            return $this->$method($matches);
        };

        return preg_replace_callback($pattern, $callback, $contents);
    }

    /**
     * Reuse function compile
     * 
     * @return string
     */
    public function compileDownload() {
        return $this->compile($isDownload = true);
    }

    /**
     * Compile foreach statement
     * 
     * @param string $pregMatch
     * 
     * @return string
     */
    protected function compileForeach($pregMatch) {
        if (preg_match('/foreach\((.*?)\)/', $pregMatch[1], $matches)) {
            $content = $matches[0];
            
            switch (strtolower(trim($matches[1]))) {
                case $this->experienceVariable:
                    $content = $this->compileExperience($pregMatch[2]);
                    break;
                
                case $this->educationVariable:
                    $content = $this->compileEducation($pregMatch[2]);
                    break;
                
                case $this->skillVariable:
                    $content = $this->compileSkill($pregMatch[2]);
                    break;
                
                case $this->socialVariable:
                    $content = $this->compileSocial($pregMatch[2]);
                    break;
                
                case $this->hobbyVariable:
                    $content = $this->compileHobby($pregMatch[2]);
                    break;
            }
            
            return $content;
        }
    }

    /**
     * Compile experiences
     * 
     * @param string $experienceRaw
     * 
     * @return string
     */
    protected function compileExperience($experienceRaw) {
        $employments = $this->resume->getEmployments();
        $content     = '';
        
        foreach ($employments as $one) {
            $content .= preg_replace_callback('/\[\[(.*?)\]\]/s', function($matches) use($one) {
                $expProperty = trim($matches[1]);
                
                if (in_array($expProperty, $this->experienceProperties)) {
                    switch($expProperty) {
                        case 'COMPANY_NAME':
                            return $one->company_name;
                            
                        case 'TIME':
                            $startTime = Carbon::parse($one->start_date)->format('m/Y');
                            $endTime   = ($one->is_current) ? _t('setting.employment.current') : Carbon::parse($one->end_date)->format('m/Y');
                            
                            return $startTime . ' - ' . $endTime;
                            
                        case 'TIME_LETTER':
                            $startTime = Carbon::parse($one->start_date)->format('M Y');
                            $endTime   = ($one->is_current) ? _t('setting.employment.current') : Carbon::parse($one->end_date)->format('M Y');
                            
                            return $startTime . ' - ' . $endTime; 
                            
                        case 'POSITION':
                            return $one->position;
                            
                        case 'DESCRIPTION':
                            return nl2br($one->achievement);
                            
                        case 'LINK':
                            return $one->company_website;
                            
                        case 'LINK_TEXT':
                            return preg_replace_callback('/(http|https):\/\//', function($match){
                                return '';
                            }, $one->company_website);
                            
                        default;
                    }
                }
            }, $experienceRaw);
        }
        
        return $content;
    }
    
    /**
     * Compile experiences
     * 
     * @param string $educationRaw
     * 
     * @return string
     */
    protected function compileEducation($educationRaw) {
        $education = $this->resume->getEducations();
        $content   = '';
        
        foreach ($education as $one) {
            $content .= preg_replace_callback('/\[\[(.*?)\]\]/s', function($matches) use($one) {
                $educationProperty = trim($matches[1]);
                
                if (in_array($educationProperty, $this->educationProperties)) {
                    switch($educationProperty) {
                        case 'COLLEGE_NAME':
                            return $one->college_name;
                            
                        case 'TIME':
                            $startTime = Carbon::parse($one->start_date)->format('m/Y');
                            $endTime   = Carbon::parse($one->end_date)->format('m/Y');
                            
                            return $startTime . ' - ' . $endTime; 
                            
                        case 'TIME_LETTER':
                            $startTime = Carbon::parse($one->start_date)->format('M Y');
                            $endTime   = Carbon::parse($one->end_date)->format('M Y');
                            
                            return $startTime . ' - ' . $endTime; 
                            
                        case 'SUBJECT':
                            return $one->subject;
                            
                        case 'QUALIFICATION':
                            return nl2br($one->qualification->name);
                            
                        default;
                    }
                }
            }, $educationRaw);
        }
        
        return $content;
    }
    
    /**
     * Compile skills
     * 
     * @param string $skillRaw
     * 
     * @return string
     */
    protected function compileSkill($skillRaw) {
        $skills  = $this->resume->getSkills();
        $content = '';
        $total   = 0;
        
        if (count($skills)) {
            foreach ($skills as $one) {
                $total += $one->votes;
            }
            
            foreach ($skills as $one) {
                $content .= preg_replace_callback('/\[\[(.*?)\]\]/s', function($matches) use($one, $total) {
                    $skillProperty = trim($matches[1]);

                    if (in_array($skillProperty, $this->skillProperties)) {
                        switch($skillProperty) {
                            case 'NAME':
                                return $one->skill->name;

                            case 'RATES':
                                return $one->votes;

                            case 'PERCENT':
                                return round(($one->votes*100)/$total);
                            default;
                        }
                    }
                }, $skillRaw);
            }
            }
        
        return $content;
    }
    
    /**
     * Compile skills
     * 
     * @param string $socialRaw
     * 
     * @return string
     */
    protected function compileSocial($socialRaw) {
        $socials = unserialize($this->resume->getSocialNetworks());
        $content = '';
        
        if (is_array($socials) && count($socials)) {
            
            foreach ($socials as $k => $one) {
                $content .= preg_replace_callback('/\[\[(.*?)\]\]/s', function($matches) use($one, $k) {
                    $socialProperty = trim($matches[1]);
                    $icons          = config('frontend.availableSocialIcons');

                    if (in_array($socialProperty, $this->socialProperties)) {
                        switch($socialProperty) {
                            case 'LINK':
                                $link = trim($one);
                                $link = str_replace('https://www.', '', $link);
                                $link = str_replace('http://www.', '', $link);
                                $link = str_replace('https://', '', $link);
                                $link = str_replace('http://', '', $link);

                                return "https://$link";
                                break;
                            case 'TEXT':
                                $urlParts = parse_url("https://$one");
                                $urlPath  = isset($urlParts['path']) ? $urlParts['path'] : "<$k>";

                                return str_replace('/', '', $urlPath);
                                break;
                            case 'ICON':
                                if (isset($icons[$k])) {
                                    return $icons[$k];
                                }
                                break;

                            default;
                        }
                    }
                }, $socialRaw);
            }
        }
        
        return $content;
    }
    
    /**
     * Compile hobbies
     * 
     * @param string $hobbyRaw
     * 
     * @return string
     */
    protected function compileHobby($hobbyRaw) {
        $hobbies = explode(',', $this->resume->getHobbies());
        $content = '';
        
        if (count($hobbies)) {
            foreach ($hobbies as $one) {
                $content .= preg_replace_callback('/\[\[(.*?)\]\]/s', function($matches) use($one) {
                    $hobbyProperty = trim($matches[1]);

                    if (in_array($hobbyProperty, $this->hobbyProperties)) {
                        switch($hobbyProperty) {
                            case 'HOBBY':
                                return $one;
                        }
                    }
                }, $hobbyRaw);
            }
        }
        
        return $content;
    }

    /**
     * Get theme configuration
     *
     * @return array
     */
    public function getConfig() {
        $config = new Config($this->configuration);

        return $config->getConfig();
    }

    /**
     * Compile asset path
     *
     * @return string
     */
    protected function compileAssets() {
        return asset($this->themesFolder . '/' . $this->getThemeName());
    }

    /**
     * Compile asset path
     *
     * @return string
     */
    protected function compileLinkStylesheetDownload() {
        if ($this->resume->getDownload()) {
            $link = $this->themesFolder . '/' . $this->getThemeName() . '/' . $this->downloadCss;
            $html = '<link rel="stylesheet" href="%s">';

            if (file_exists($link)) {
                return sprintf($html, asset($link));
            }
        }

        return false;
    }

    /**
     * Compile asset path
     *
     * @return string
     */
    protected function compileCommonAssets() {
        return asset($this->commonThemeAssetsFolder);
    }

    /**
     * Compile configuration
     *
     * @param string $pregMatch Config raw
     *
     * @param type $pregMatch
     */
    protected function compileConfig($pregMatch) {
        $config    = '';
        $configRaw = isset($pregMatch[2]) ? $pregMatch[2] : '';
        $configTag = isset($pregMatch[1]) ? $pregMatch[1] : '';

        if (preg_match('/config/', $configTag)) {
            $config = $configRaw;
        }

        $this->configuration = $config;
    }

    /**
     * Compile first name
     * 
     * @return string 
     */
    protected function compileFirstName() {
        return $this->resume->getFirstName();
    }
    
    /**
     * Compile first name first letter
     * 
     * @return string 
     */
    protected function compileFirstNameLetter() {
        return substr($this->resume->getFirstName(), 0, 1);
    }
    
    /**
     * Compile last name
     * 
     * @return string 
     */
    protected function compileLastName() {
        return $this->resume->getLastName();
    }
    
    /**
     * Compile last name
     * 
     * @return string 
     */
    protected function compileLastNameLetter() {
        return substr($this->resume->getLastName(), 0, 1);
    }
    
    /**
     * Compile last name
     * 
     * @return string 
     */
    protected function compileFullName() {
        return $this->resume->getFirstName() . ' ' . $this->resume->getLastName();
    }
    
    /**
     * Compile avatar
     * 
     * @return string 
     */
    protected function compileAvatar() {
        return $this->getAvatar('original');
    }
    
    /**
     * Compile avatar 128
     * 
     * @return string 
     */
    protected function compileAvatar128() {
        return $this->getAvatar(128);
    }
    
    /**
     * Compile avatar 256
     * 
     * @return string 
     */
    protected function compileAvatar256() {
        return $this->getAvatar(256);
    }
    
    /**
     * Compile avatar 512
     * 
     * @return string 
     */
    protected function compileAvatar512() {
        return $this->getAvatar(512);
    }
    
    /**
     * Compile avatar
     * 
     * @return string 
     */
    protected function compileCover() {
        return $this->getCover('original');
    }
    
    /**
     * Compile avatar 128
     * 
     * @return string 
     */
    protected function compileCover768() {
        return $this->getCover(768);
    }
    
    /**
     * Compile avatar 256
     * 
     * @return string 
     */
    protected function compileCover960() {
        return $this->getCover(960);
    }
    
    /**
     * Compile avatar 512
     * 
     * @return string 
     */
    protected function compileCover1200() {
        return $this->getCover(1220);
    }
    
    /**
     * Compile contact website name
     *
     * @return string Without http://www
     */
    protected function compileWebsiteName() {
        $website = trim($this->resume->getWebsite());
        $website = str_replace('https://www.', '', $website);
        $website = str_replace('http://www.', '', $website);
        $website = str_replace('https://', '', $website);
        $website = str_replace('http://', '', $website);

        return $website;
    }

    /**
     * Compile contact website link
     *
     * @return string Without http://www
     */
    protected function compileWebsiteLink() {
        $website = trim($this->resume->getWebsite());

        if (strpos($website, 'https://') === false && strpos($website, 'http://') === false) {
            $website = "https://$website";
        }

        return $website;
    }
    
    /**
     * Compile contact phone number
     * 
     * @return string
     */
    protected function compilePhoneNumber() {
        return $this->resume->getPhoneNumber();
    }
    
    /**
     * Compile contact email
     * 
     * @return string
     */
    protected function compileEmail() {
        return $this->resume->getEmail();
    }
    
    /**
     * Compile country name
     * 
     * @return string
     */
    protected function compileStreet() {
        return $this->resume->getStreetName();
    }
    
    /**
     * Compile country name
     * 
     * @return string
     */
    protected function compileCountry() {
        $country = $this->resume->getCountry();
        
        return isset($country['country_name']) ? $country['country_name'] : '';
    }
    
    /**
     * Compile country code
     * 
     * @return string
     */
    protected function compileCountryCode() {
        $country = $this->resume->getCountry();
        
        return isset($country['country_code']) ? $country['country_code'] : '';
    }
    
    /**
     * Compile city name
     * 
     * @return string
     */
    protected function compileCity() {
        return $this->resume->getCity();
    }
    
    /**
     * Compile city type
     * 
     * @return string
     */
    protected function compileCityType() {
        $city = $this->resume->getCity();
        
        return isset($city['type']) ? $city['type'] : '';
    }
    
    /**
     * Compile district name
     * 
     * @return string
     */
    protected function compileDistrict() {
        $district = $this->resume->getDistrict();
        
        return isset($district['name']) ? $district['name'] : '';
    }
    
    /**
     * Compile district type
     * 
     * @return string
     */
    protected function compileDistrictType() {
        $district = $this->resume->getDistrict();
        
        return isset($district['type']) ? $district['type'] : '';
    }
    
    /**
     * Compile ward name
     * 
     * @return string
     */
    protected function compileWard() {
        $ward = $this->resume->getWard();
        
        return isset($ward['name']) ? $ward['name'] : '';
    }
    
    /**
     * Compile about me
     * 
     * @return string
     */
    protected function compileAboutMe() {
        return nl2br($this->resume->getAboutMe());
    }
    
    /**
     * Compile gender
     * 
     * @return string
     */
    protected function compileGender() {
        $gender = $this->resume->getGender();
        
        return isset($gender['gender_name']) ? $gender['gender_name'] : '';
    }
    
    /**
     * Compile date of birth
     * 
     * @return string
     */
    protected function compileDatefBirth() {
        return (($d = ((int) $this->getDobDateTime()->format('d'))) < 10) ? "0$d" : $d;
    }
    
    /**
     * Compile month of birth
     * 
     * @return string
     */
    protected function compileMonthOfBirth() {
         return (($m = ((int) $this->getDobDateTime()->format('m'))) < 10) ? "0$m" : $m;
    }
    
    /**
     * Compile year of birth
     * 
     * @return string
     */
    protected function compileYearOfBirth() {
         return $this->getDobDateTime()->format('Y');
    }
    
    /**
     * Compile birthday
     * 
     * @return string
     */
    protected function compileBirthDay() {
        if (null !== $this->resume->getDob()) {
            $dob = new \DateTime($this->resume->getDob());
            
            return $dob->format('M d, Y');
        }
        
        return '';
    }
    
    /**
     * Compile marital status
     * 
     * @return string
     */
    protected function compileMaritalStatus() {
        $status = $this->resume->getMaritalStatus();
        
        return isset($status['name']) ? $status['name'] : '';
    }
    
    /**
     * Compile age
     * 
     * @return string
     */
    protected function compileAge() {
        return ((int) date('Y')) - ((int) $this->getDobDateTime()->format('Y'));
    }
    
    /**
     * Compile expected job
     * 
     * @return string
     */
    protected function compileExpectedJob() {
        return $this->resume->getExpectedJob();
    }
    
    /**
     * Compile hobbies
     * 
     * @return string
     */
    protected function compileHobbies() {
        return $this->resume->getHobbies();
    }


    protected function compilePdfWrapperClass() {
        return config('frontend.pdfWrapperClass');
    }

    /**
     * Get day of birth
     * 
     * @return \DateTime
     */
    protected function getDobDateTime() {
        return new \DateTime($this->resume->getDob());
    }

    /**
     * Generate the path to the file currently being compiled
     * 
     * @param bool $isDownload Get the html template for download or for preview.
     * 
     * @return string
     */
    protected function generateFilenamePath($isDownload = false) {
        
        $themePath = base_path($this->publicFolder . '/' . $this->themesFolder . '/' . $this->getThemeName() . '/');
        $fileName  = $this->filename;
        
        if ($isDownload && check_file($themePath . $this->download)) {
            $fileName = $this->download;
        }
        
        return $themePath . $fileName;
    }
    
    /**
     * Get cover image path
     * 
     * @param string|int $size
     * 
     * @return string
     */
    protected function getAvatar($size) {
        $avatarImg     = unserialize($this->resume->getAvatarImages());
        $avatar        = config('frontend.avatarsFolder') . '/' . (isset($avatarImg[$size]) ? $avatarImg[$size] : '');
        $avatarDefault = config('frontend.avatarDefault');
        
        return asset((check_file($avatar)) ? $avatar : $avatarDefault);
    }
    
    /**
     * Get cover image path
     * 
     * @param string|int $size
     * 
     * @return string
     */
    protected function getCover($size) {
        $coverImg     = unserialize($this->resume->getCoverImages());
        $cover        = config('frontend.coversFolder') . '/' . (isset($coverImg[$size]) ? $coverImg[$size] : '');
        $coverDefault = config('frontend.coverDefault');
        
        return asset((check_file($cover)) ? $cover : $coverDefault);
    }
}
