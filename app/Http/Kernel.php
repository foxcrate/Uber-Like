<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\setlanguage::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,

        ],

        'api' => [
            'throttle:120,1',
            'bindings',
            //  \App\Library\Cobalt\Http\Middleware\LogMiddleware::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'language' => \App\Http\Middleware\Lang::class,
        'lang' => \App\Http\Middleware\langs::class,
        'account' => \App\Http\Middleware\RedirectIfNotAccount::class,
        'account.guest' => \App\Http\Middleware\RedirectIfAccount::class,
        'fleet' => \App\Http\Middleware\RedirectIfNotFleet::class,
        'fleet.guest' => \App\Http\Middleware\RedirectIfFleet::class,
//        'company' => \App\Http\Middleware\RedirectIfFleet::class,
        'dispatcher' => \App\Http\Middleware\RedirectIfNotDispatcher::class,
        'dispatcher.guest' => \App\Http\Middleware\RedirectIfDispatcher::class,
        'provider' => \App\Http\Middleware\RedirectIfNotProvider::class,
        'provider.guest' => \App\Http\Middleware\RedirectIfProvider::class,
        'provider.api' => \App\Http\Middleware\ProviderApiMiddleware::class,
        'admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,
        'admin.guest' => \App\Http\Middleware\RedirectIfAdmin::class,
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'jwt.auth' => 'Tymon\JWTAuth\Middleware\GetUserFromToken',
        'jwt.refresh' => 'Tymon\JWTAuth\Middleware\RefreshToken',
        'setlanguage' => \App\Http\Middleware\setlanguage::class,
//        'role' => \Zizaco\Entrust\Middleware\EntrustRole::class,
//        'permission' => \Zizaco\Entrust\Middleware\EntrustPermission::class,
//        'ability' => \Zizaco\Entrust\Middleware\EntrustAbility::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,

        'auto-check-permission' => Middleware\AutoCheckPermission::class,

        /**** OTHER MIDDLEWARE ****/
        'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
        'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
    ];
}
