<?php
Route::group(['middleware' => ['web', 'master'], 'prefix' => 'kamehameha'], function ($route) {
    $route->get('/', 'DashboardController@index')->name('back_dashboard');

    $route->get('users', 'UserController@index')->name('back_users');
    $route->get('user/{id}/view', 'UserController@view')->where('id', '[0-9]+')->name('back_user_view');
    $route->post('user/update_status', 'UserController@updateStatus')->name('back_user_status');
    $route->post('user/remove', 'UserController@remove')->name('back_user_remove');

    $route->get('themes', 'ThemeController@index')->name('back_themes');
    $route->get('theme/{id}/view', 'ThemeController@view')->name('back_theme_view');
    $route->get('theme/{id}/edit', 'ThemeController@edit')->name('back_theme_edit');
    $route->post('theme/save', 'ThemeController@save')->name('back_theme_save');
    $route->post('theme/update_status', 'ThemeController@updateStatus')->name('back_theme_status');
    $route->post('theme/remove', 'ThemeController@remove')->name('back_theme_remove');
    $route->get('theme/generate-screenshot', 'ThemeController@generateScreenshot')->name('back_theme_screenshot');

    $route->get('pages', 'PageController@index')->name('back_pages');
    $route->get('page/add', 'PageController@add')->name('back_page_add');
    $route->get('page/{id}/edit', 'PageController@edit')->where('id', '[0-9]+')->name('back_page_edit');
    $route->post('page/block', 'PageController@block')->name('back_page_block');
    $route->post('page/save', 'PageController@save')->name('back_page_save');
});