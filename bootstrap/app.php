<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetTahunAnggaran::class,
        ]);
        $middleware->alias([
            'access' => \App\Http\Middleware\CheckAccess::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'check.perorangan.detail' => \App\Http\Middleware\CheckPeroranganDetail::class,
            'ensure.user.active' => \App\Http\Middleware\EnsureUserActive::class,
            'ensure.organisasi.not_blacklisted' => \App\Http\Middleware\EnsureOrganisasiNotBlacklisted::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
