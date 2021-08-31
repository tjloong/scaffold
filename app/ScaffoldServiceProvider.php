<?php

namespace Jiannius\Scaffold;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ScaffoldServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/scaffold.php', 'scaffold');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCore();
        $this->registerAuth();
    }

    /**
     * Register core features
     * 
     * @return void
     */
    public function registerCore()
    {
        // Blade directive: @route
        Blade::if('route', function($value) {
            $route = request()->route()->getName() ?? request()->path();
            return $route === $value;
        });

        // Blade components
        $this->loadViewsFrom(__DIR__.'/../resources/views/components', 'scaffold-component');
        Blade::componentNamespace('Jiannius\\Scaffold\\Components', 'scaffold-component');

        // publishing
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/scaffold.php' => config_path('scaffold.php'),
                __DIR__.'/../stubs/Core/Controller.php' => app_path('Http/Controllers/Controller.php'),
            ], 'scaffold-core');
        }
    }

    /**
     * Register auth module
     * 
     * @return void
     */
    public function registerAuth()
    {
        Route::group(['middleware' => 'web'], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views/auth', 'scaffold-auth');

        // publishing
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/Auth/AuthController.php' => app_path('Http/Controllers/AuthController.php'),
                __DIR__.'/../resources/views/auth' => resource_path('views/auth'),
            ], 'scaffold-auth');
        }
    }
}