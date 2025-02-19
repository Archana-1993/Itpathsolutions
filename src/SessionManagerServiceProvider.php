<?php

namespace Itpathsolutions\Sessionmanager;

use Illuminate\Support\ServiceProvider;
use Itpathsolutions\Sessionmanager\Http\Middleware\RoleBasedSessionMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;


class SessionManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Spatie
        /* $this->publishes([
             __DIR__ . '/../vendor/spatie/laravel-permission/config/permission.php' => config_path('permission.php'),
        ], 'config');
        $migrationsPath = __DIR__ . '/../vendor/spatie/laravel-permission/database/migrations';
        $targetPath = database_path('migrations');

        if (file_exists($migrationsPath)) {
            foreach (glob($migrationsPath . '/*.stub') as $file) {
                $newFilename = pathinfo($file, PATHINFO_FILENAME);
                $newFilePath = $targetPath . '/' . $newFilename;
                copy($file, $newFilePath);
            }
        } */
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'sessionmanager');
        // Custom package
        $this->loadMigrationsFrom(database_path('migrations'));
        $this->publishes([
            __DIR__.'/Config/sessionmanager.php' => config_path('sessionmanager.php'),
        ], 'config');
        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations'),
        ], 'migrations');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app['router']->aliasMiddleware('session.timeout', RoleBasedSessionMiddleware::class);

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role.session', RoleBasedSessionMiddleware::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/sessionmanager.php', 'sessionmanager');
    }

    private function setSessionLifetime()
    {
        // if (Auth::check()) {
        //     $user = Auth::user();

        //     if ($user->hasRole('admin')) {
        //         Config::set('session.lifetime', config('sessionmanager.admin_session_lifetime', 30));
        //     } else {
        //         Config::set('session.lifetime', config('sessionmanager.user_session_lifetime', 20));
        //     }
        // }
    }
}
