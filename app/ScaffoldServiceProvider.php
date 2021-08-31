<?php

namespace Jiannius\Scaffold;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Jiannius\Scaffold\Commands\InstallCommand;

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

        // Helpers
        require_once __DIR__.'/../app/Helpers.php';

        // publishing
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/scaffold.php' => config_path('scaffold.php'),
                __DIR__.'/../resources/views/errors' => resource_path('views/errors'),
                __DIR__.'/../stubs/Env' => base_path(),
            ], 'scaffold-core');

            $this->commands([
                InstallCommand::class,
            ]);
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

        // add laravel sanctum middleware to kernel
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
            ->prependMiddlewareToGroup('api', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);

        // publishing
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/Auth/AuthController.php' => app_path('Http/Controllers/AuthController.php'),
                __DIR__.'/../resources/views/auth' => resource_path('views/auth'),
            ], 'scaffold-auth');
        }
    }
}