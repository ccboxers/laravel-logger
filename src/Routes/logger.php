<?php

use Illuminate\Support\Facades\Route;
use Layman\LaravelLogger\Controllers\HomeController;
use Layman\LaravelLogger\Controllers\LoginController;

Route::middleware('web')->prefix('logger')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('logger.login');
    Route::post('login', [LoginController::class, 'login'])->name('logger.login');
    Route::post('logout', [LoginController::class, 'logout'])->name('logger.logout');
    Route::get('/home', [HomeController::class, 'index'])->name('logger.home');
});




