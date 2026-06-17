<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Illuminate\Support\Facades\Route::middleware('web')
                ->prefix('hr')
                ->name('hr.')
                ->group(base_path('routes/hr.php'));

            Illuminate\Support\Facades\Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // Student panel routes are loaded directly inside routes/student.php
            // with their own prefix and middleware
            Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/student.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'faculty' => \App\Http\Middleware\IsFaculty::class,
            'ads'     => \App\Http\Middleware\EnsureAdsUser::class,
            'hr'      => \App\Http\Middleware\EnsureHrUser::class,
            'admin'   => \App\Http\Middleware\EnsureAdminUser::class,
        ]);

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            if ($request->is('hr') || $request->is('hr/*')) {
                return route('hr.login');
            }
            if ($request->is('faculty') || $request->is('faculty/*')) {
                return route('faculty.login');
            }
            if ($request->is('ads') || $request->is('ads/*')) {
                return route('ads.login');
            }
            if ($request->is('admission') || $request->is('admission/*')) {
                return route('admission.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
