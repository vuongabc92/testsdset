<?php

namespace App\Providers;
use Schema;
use Validator;
use App\Helpers\Blade;
use Illuminate\Support\ServiceProvider;
use App\Validations\Activated;
use App\Helpers\Validation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadHelpers();

        new Activated();
        new Blade();
        new Validation();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     *
     */
    protected function loadHelpers()
    {
        require_once __DIR__ . '/../Helpers/functions.php';
    }
}
