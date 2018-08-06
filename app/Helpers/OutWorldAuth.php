<?php
namespace App\Helpers;

use Facebook\Facebook;
use Google_Client;
use Google_Service_Oauth2;

trait OutWorldAuth {
    
    protected $facebookApi;
    protected $facebookPermissions = ['email', 'public_profile', 'user_photos'];
    protected $fbLoginCallbackRoute;
    protected $googleLoginCallbackRoute;
    protected $googleClientId;
    protected $googleClientSecret;
    protected $googleAppName;
    protected $googleAccessToken;
    protected $googleClient;


    public function __construct() {
        $this->facebookApi              = config('frontend.facebook_api');
        $this->fbLoginCallbackRoute     = route('front_login_with_fbcallback');
        
        $this->googleLoginCallbackRoute = route('front_login_with_gcallback');
        $this->googleClientId           = config('frontend.google_api.client_id');
        $this->googleClientSecret       = config('frontend.google_api.client_secret');
        $this->googleAppName            = config('frontend.google_api.app_name');
    }
    
    /**
     * Get facebook instance
     * 
     * @return Facebook
     */
    public function facebook() {
        return new Facebook($this->facebookApi);
    }
    
    /**
     * Get facebook auth URL
     * 
     * @return string
     */
    public function facebookAuthUrl() {
        $helper = $this->facebook()->getRedirectLoginHelper();
        
        return $helper->getLoginUrl($this->fbLoginCallbackRoute, $this->facebookPermissions);
    }
    
    /**
     * get google client
     * 
     * @return Google_Client
     */
    public function googleClient() {
        $client = new Google_Client();
        
        $client->setApplicationName($this->googleAppName);
        $client->setClientId($this->googleClientId);
        $client->setClientSecret($this->googleClientSecret);
        $client->setRedirectUri($this->googleLoginCallbackRoute);
        $client->addScope('email');
        $client->addScope('profile');
        
        $this->googleClient = $client;
        
        return $client;
    }
    
    /**
     * Get Google authenticate URL
     * 
     * @return string Google authenticate URL
     */
    public function googleAuthUrl() {
        return $this->googleClient()->createAuthUrl();
    }
    
    /**
     * Google service
     * 
     * @return Google_Service_Oauth2
     */
    public function googleService() {
        return new Google_Service_Oauth2($this->googleClient);
    }
    
    /**
     * Get google user info
     * 
     * @return array
     */
    public function googleUserInfo() {
        return $this->googleService()->userinfo->get();
    }
}
