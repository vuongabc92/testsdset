<?php

Route::group(['middleware' => ['auth:web']], function ($route) {
    $route->get('settings', 'SettingsController@index')->name('front_settings');
    $route->post('settings/publish_profile', 'SettingsController@publishProfile')->name('front_setting_publish_profile');
    $route->post('settings/upload_avatar', 'SettingsController@uploadAvatar')->name('front_settings_upload_avatar');
    $route->post('settings/upload_cover', 'SettingsController@uploadCover')->name('front_settings_upload_cover');
    $route->post('settings/save_info', 'SettingsController@saveInfo')->name('front_settings_save_info');
    $route->post('settings/select_place', 'SettingsController@createAddressSelectData')->name('front_settings_select_place');
    $route->get('settings/employment_history/{id?}', 'SettingsController@getEmploymentHistoryById')->where('id', '[0-9]+')->name('front_settings_employmentbyid');
    $route->delete('settings/employment_history_remove', 'SettingsController@removeEmploymentHistoryById')->name('front_settings_employmentremovebyid');
    $route->delete('settings/education_history_remove', 'SettingsController@removeEducationHistoryById')->name('front_settings_educationremovebyid');
    $route->get('settings/education_history/{id?}', 'SettingsController@getEducationHistoryById')->where('id', '[0-9]+')->name('front_settings_educationbyid');
    $route->delete('settings/kill_tag', 'SettingsController@killTag')->name('front_settings_killtag');
    $route->delete('settings/kill_social', 'SettingsController@killSocial')->name('front_settings_killsocial');
    $route->get('settings/search_skill/{keyword?}', 'SettingsController@searchSkill')->name('front_settings_searchskill');
    $route->get('settings/theme', 'SettingsController@theme')->name('front_settings_theme');
    $route->post('theme/install', 'SettingsController@install')->name('front_theme_install');
    $route->post('theme/add_new', 'SettingsController@addNewTheme')->name('front_theme_add_new')->middleware('master');
    $route->get('theme/{slug}/preview', 'ResumeController@preview')->name('front_theme_preview');
    $route->get('theme/{slug}/download/{height}', 'ResumeController@download')->where('height', '[0-9]+')->name('front_theme_download');
});

// Authentication Routes.
Route::get('login', 'Auth\LoginController@showLoginForm')->name('front_login');
Route::post('login', 'Auth\LoginController@login')->name('front_login_post');
Route::get('logout', 'Auth\LoginController@logout')->name('front_logout');
Route::get('facebook-authenticate', 'Auth\LoginController@loginWithFBCallback')->name('front_login_with_fbcallback');
Route::get('google-authenticate', 'Auth\LoginController@loginWithGoogleCallback')->name('front_login_with_gcallback');

// Registration Routes.
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('front_register');
Route::post('register', 'Auth\RegisterController@register')->name('front_register_post');

// Password Reset Routes.
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('front_forgotpass');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('front_forgotpass_post');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('front_resetpass');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('front_resetpass_post');

Route::get('/', 'IndexController@index')->name('front_index');
Route::get('/contact', 'IndexController@contact')->name('front_contact');
Route::get('/developer', 'IndexController@developer')->name('front_developer');
Route::get('/privacy-policy', 'IndexController@privacyPolicy')->name('front_privacy_policy');
Route::get('/terms-and-conditions', 'IndexController@termsAndConditions')->name('front_terms_conditions');

Route::get('more-themes', 'SettingsController@lazyLoadTheme')->name('front_themes_lazy');
Route::get('theme/{slug}/details', 'SettingsController@themeDetails')->name('front_theme_details');

Route::get('i/{slug?}', 'ResumeController@index')->name('front_cv');