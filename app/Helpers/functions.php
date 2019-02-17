<?php
if ( ! function_exists('_t')) :

    /**
     * Translate the given message.
     *
     * @param  string  $id
     * @param  array   $parameters
     * @param  string  $domain
     * @param  string  $locale
     *
     * @return string
     */
    function _t($id = null, $parameters = [], $domain = 'messages', $locale = null) {
        return trans("frontend.{$id}", $parameters, $domain, $locale);
    }

endif; // Function _t.

if ( ! function_exists('_error') ) {
    /**
     * Get AJAX error status string
     *
     * @return string
     */
    function _error() {
        return 'ERROR';
    }
}

if ( ! function_exists('_ok') ) {
    /**
     * Get AJAX success status string
     *
     * @return string
     */
    function _ok() {
        return 'OK';
    }
}

if ( ! function_exists('pong')) :

    /**
     * Return a new simple JSON response from the application.
     *
     * @param  string|array $messages
     * @param  string       $status 0 = ERROR and 1 = OK
     * @param  int          $status_code
     * @param  array        $headers
     * @param  int          $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function pong($messages, $status = 'OK', $status_code = 200, array $headers = [], $options = 0) {

        if (is_array($messages)) {
            $data = array_merge(['status'   => $status], $messages);
        } else {
            $data = [
                'status'   => $status,
                'messages' => $messages
            ];
        }

        return response()->json($data, $status_code, $headers, $options);
    }

endif; // Function pong.

if ( ! function_exists('file_pong')) :

    /**
     * Return a new JSON response from the application.
     *
     * @param  string|array  $data
     * @param  int           $status
     * @param  int           $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function file_pong($messages, $status = 'OK', $status_code = 200, array $headers = ['Content-Type' => 'text/html'], $options = 0) {
        return pong($messages, $status, $status_code, $headers, $options );
    }

endif; // Function file_pong.

if ( ! function_exists('user')) :
    /**
     * Current authenticated user
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function user() {
        if (auth()->check()) {
            return auth()->user();
        }

        return null;
    }
endif; // Function user.

if ( ! function_exists('user_id')) :
    /**
     * Current authenticated user id
     *
     * @return int User id
     */
    function user_id() {
        return user()->id;
    }
endif; // Function user_id.

if ( ! function_exists('str_equal')) :
    /**
     * Compares two strings using a constant-time algorithm.
     *
     * Note: This method will leak length information.
     *
     * Note: Adapted from Symfony\Component\Security\Core\Util\StringUtils.
     *
     * @param  string  $knownString
     * @param  string  $userInput
     *
     * @return bool
     */
    function str_equal($knownString, $userInput) {
        return \Illuminate\Support\Str::equals($knownString, $userInput);
    }
endif;

if ( ! function_exists('remove_rules')) :
    /**
     * Remove one or many rules in a list of rules
     *
     * @param array        $rules       List of rules will be removed out
     * @param array|string $rulesRemove Rule to be found in $rules to remove
     *
     * @return array
     */
    function remove_rules($rules, $rulesRemove) {

        //Remove list rules
        if (is_array($rulesRemove) && count($rulesRemove)) {
            foreach ($rulesRemove as $one) {
                $rules = remove_rules($rules, $one);
            }

            return $rules;
        }

        /**
         * Remove rule string
         * 1. If rule contains dot "." then remove rule after dot for rule name
         *    before the dot.
         * 2. If rule doesn't contain dot then remove the rule name present
         *
         */
        if (is_string($rulesRemove)) {

            if (str_contains($rulesRemove, '.')) {
                $ruleInField = explode('.', $rulesRemove);
                if (isset($rules[$ruleInField[0]])) {
                    $ruleSplit = explode('|', $rules[$ruleInField[0]]);
                    $ruleFlip  = array_flip($ruleSplit);

                    if (isset($ruleFlip[$ruleInField[1]])) {
                        unset($ruleSplit[$ruleFlip[$ruleInField[1]]]);
                    }

                    //Remove the rule name if it contains no rule
                    if (count($ruleSplit)) {
                        $rules[$ruleInField[0]] = implode('|', $ruleSplit);
                    } else {
                        unset($rules[$ruleInField[0]]);
                    }
                }

            } elseif (isset($rules[$rulesRemove])) {
                unset($rules[$rulesRemove]);
            }

            return $rules;
        }

        return $rules;
    }
endif;

if ( ! function_exists('generate_filename')) :
    /**
     * Generate the file name base on current user id, time
     * to get a unique file in present directory
     *
     * @param string $directory Path to the upload directory
     * @param string $extension File extension
     * @param array  $options   Prefix, suffix,...
     *
     * @return string
     */
    function generate_filename($directory, $extension, $options = []) {
        $prefix    = isset($options['prefix']) ? $options['prefix']  : '';
        $suffix    = isset($options['suffix']) ? $options['suffix']  : '';
        $limit     = isset($options['limit'])  ? (int) $options['limit'] : 16;
        $randomStr = random_string($limit, $available_sets = 'lud');
        $fileName  = $prefix . $randomStr . $suffix . '.' . $extension;

        while (is_file($directory . $fileName)) {
            $fileName = generate_filename($directory, $extension, $options);
        }

        return $fileName;
    }
endif;

if ( ! function_exists('random_string')) {
    /**
     * <code>https://gist.github.com/tylerhall/521810</code>
     *
     * Generates a string of N length containing at least one lower case letter,
     * one uppercase letter, one digit, and one special character. The remaining characters
     * in the string are chosen at random from those four sets.
     * The available characters in each set are user friendly - there are no ambiguous
     * characters such as i, l, 1, o, 0, etc.
     *
     * @param int    $length         Length of the password will be generated
     * @param string $available_sets dedaults sets include letter (l), uppercase (u), digit (d), special character (s)
     *
     * @return string
     */
    function random_string($length = 8, $available_sets = 'luds') {
        $sets     = array();
        $all      = '';
        $password = '';

        if(strpos($available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if(strpos($available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if(strpos($available_sets, 'd') !== false) {
            $sets[] = '23456789';
        }
        if(strpos($available_sets, 's') !== false) {
            $sets[] = '!@#$%&*?';
        }

        foreach($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all      .= $set;
        }

        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }

        return str_shuffle($password);
    }
}

if ( ! function_exists('random_string_with_dashes')) {
    /**
     * Add dashes to the random string.
     *
     * @param int    $length         Length of the password will be generated
     * @param string $available_sets dedaults sets include letter (l), uppercase (u), digit (d), special character (s)
     *
     * @return string
     */
    function random_string_with_dashes($length = 8, $available_sets = 'luds') {
        $randomString = random_string($length, $available_sets);
        $dash_len     = floor(sqrt($length));
        $dash_str     = '';

        while(strlen($randomString) > $dash_len) {
            $dash_str    .= substr($randomString, 0, $dash_len) . '-';
            $randomString = substr($randomString, $dash_len);
        }

        $dash_str .= $randomString;

        return $dash_str;
    }
}

if ( ! function_exists('check_file')) :
    /**
     * Check does the present file exist
     *
     * @param string $file Path to file
     *
     * @return boolean
     */
    function check_file($file) {

        if ( ! is_dir($file) && file_exists($file)) {
            return true;
        }

        return false;
    }
endif;

if ( ! function_exists('delete_file')) :
    /**
     * Delete file
     *
     * @param string|array $file
     *
     * @return boolean
     *
     * @throws \Exception
     */
    function delete_file($file) {

        //Delete list of files
        if (is_array($file) && count($file)) {
            foreach ($file as $one) {
                delete_file($one);
            }

            return true;
        }

        if (check_file($file)) {
            try {
                \Illuminate\Support\Facades\File::delete($file);
            } catch (Exception $ex) {
                throw new \Exception('Whoop!! Can not delete file. ' . $ex->getMessage());
            }
        }

        return true;
    }
endif;

if ( ! function_exists('birthdate')) {
    /**
     * Get date for birthday selecter generation.
     *
     * @return array
     */
    function birthdate() {
        $date = ['' => _t('setting.profile.birthdate')];
        for ($d = 1; $d <= 31; $d++) {
            $fullDate        = ($d < 10) ? '0' . $d : $d;
            $date[$fullDate] = $fullDate;
        }

        $month = ['' => _t('setting.profile.birthmonth')];
        for ($m = 1; $m <= 12; $m++) {
            $FullMonth         = ($m < 10) ? '0' . $m : $m;
            $month[$FullMonth] = $FullMonth;
        }

        $year = ['' => _t('setting.profile.birthyear')];
        for ($y = (((int) date('Y')) - 15); $y >= ((int) date('Y') - 85); $y--) {
            $year[$y] = ($y < 10) ? '0' . $y : $y;
        }

        $userProfile = user()->userProfile;
        if (is_null($userProfile)) {
            $userProfile = new App\Models\UserProfile();
        }

        if (null === $userProfile->day_of_birth) {
            $birthday = ['d' => null, 'm' => null, 'y' => null];
        } else {
            $dateObj  = new \DateTime($userProfile->day_of_birth);
            $birthday = [
                'd' => (strlen($dateObj->format('d')) < 2) ? '0' . $dateObj->format('d') : $dateObj->format('d'),
                'm' => (strlen($dateObj->format('m')) < 2) ? '0' . $dateObj->format('m') : $dateObj->format('m'),
                'y' => $dateObj->format('Y')
            ];
        }

        return array('date' => $date, 'month' => $month, 'year' => $year, 'birthday' => $birthday);
    }
}// End birthdate.

if ( ! function_exists('selector_date')) {
    function selector_date($type = 'start', $year_limit = 30, $extra_end = 0) {

        $month = ('start' === $type) ? ['' => _t('setting.employment.startdate1')] : ['' => _t('setting.employment.enddate1')];
        $year  = ('start' === $type) ? ['' => _t('setting.employment.startdate2')] : ['' => _t('setting.employment.enddate2')];

        for ($m = 1; $m <= 12; $m++) {
            $FullMonth         = ($m < 10) ? '0' . $m : $m;
            $month[$FullMonth] = $FullMonth;
        }

        for ($y = ((int) date('Y') + $extra_end); $y >= ((int) date('Y') - $year_limit); $y--) {
            $year[$y] = $y;
        }

        return array('m' => $month, 'y' => $year);
    }
}

if ( ! function_exists('qualification')) {
    function qualification() {
        $quaArr = ['' => _t('setting.education.selectdefault')];

        App\Models\Qualification::all()->each(function($v, $k) use(&$quaArr) {
            $quaArr[$v->id] = $v->name;
        });

        return $quaArr;
    }
}

if ( ! function_exists('social_profile_list')) {
    function social_profile_list() {
        $socialRaw = user()->userProfile->social_network;
        $socialArr = unserialize($socialRaw);
        $icons     = config('frontend.availableSocialIcons');
        if (is_array($socialArr) && count($socialArr)) {
            $listWithIcon = [];
            foreach ($socialArr as $k => $one) {
                $listWithIcon[$k]         = array();
                $listWithIcon[$k]['link'] = (false === strpos('http', $one)) ? 'http://' . $one : $one;

                if (isset($icons[$k])) {
                    $listWithIcon[$k]['icon'] = $icons[$k];
                }
            }

            return $listWithIcon;
        }

        return [];
    }
}

if ( ! function_exists('getSlugRemainDay')) {
    /**
     * get the remain day to update cv slug
     *
     * @return int
     */
    function getSlugRemainDay() {
        if ( ! is_null(user()->userProfile->slug_updated_at)) {
            $now           = new \DateTime('now');
            $slugUpdatedAt = new \DateTime(user()->userProfile->slug_updated_at);
            $limitedDate   = config('frontend.dayLimitedToChangeSlug');
            $slugUpdatedAt->modify("+{$limitedDate} days");

            if ($slugUpdatedAt <= $now) {
                return 0;
            }

            return $slugUpdatedAt->diff(new \DateTime('now'))->days;
        }

        return 0;
    }
}

if ( ! function_exists('asset_img') ) {
    function asset_img($file_name) {
        return asset("assets/frontend/images/{$file_name}");
    }
}

if ( ! function_exists('check_route_name')) {
    function check_route_name($route_name) {
        return request()->route()->getName() === $route_name;
    }
}