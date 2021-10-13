<?php

use App\Http\Controllers\Spotify\DeviceController;
use App\Http\Controllers\Spotify\PlayerController;
use App\Http\Controllers\Spotify\PlaylistController;
use App\Http\Controllers\Spotify\TrackController;
use App\Http\Controllers\Spotify\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Spotify\AuthenticationController as SpotifyAuthenticationController;

Route::get('', [HomeController::class, 'index'])->name('index');

Route::group(
    [
        'prefix' => 'dashboard',
        'as' => 'dashboard.',
        'middleware' => ['auth', 'reauthenticate'],
    ],
    static function () {
        Route::get('', [DashboardController::class, 'index'])->name('index');

        Route::get('history', [DashboardController::class, 'history'])->name('history');
    }
);

Route::get('image', [DashboardController::class, 'image'])->name('image');

Route::group(
    [
        'prefix' => 'spotify',
        'as' => 'spotify.',
        'middleware' => 'auth',
    ],
    static function () {
        Route::middleware(['auth', 'reauthenticate'])->group(
            static function () {
                Route::get('profile', [UserController::class, 'profile'])->name('profile');

                Route::get('weekly/{id}', [UserController::class, 'toggleWeeklyPlaylists'])->name('toggleWeekly');

                Route::get('top/{type}', [UserController::class, 'top'])->name('top');
                Route::get('tracks', [UserController::class, 'tracks'])->name('tracks');
                Route::get('tracks/{offset}', [UserController::class, 'tracksOffset'])->name('tracks.offset');

                Route::get('queue/add/{uri}', [UserController::class, 'addToQueue'])->name('queue.add');

                Route::get('recommended', [UserController::class, 'recommended'])->name('recommended');

                Route::group(
                    [
                        'prefix' => 'playlists',
                        'as' => 'playlists.',
                    ],
                    static function () {
                        Route::get('', [UserController::class, 'playlists'])->name('index');
                        Route::get('{playlist}', [PlaylistController::class, 'show'])->name('show');
                    }
                );

                Route::group(
                    [
                        'prefix' => 'tracks',
                        'as' => 'tracks.',
                    ],
                    static function () {
                        Route::get('{track}/view', [TrackController::class, 'show'])->name('show');
                    }
                );

                Route::get('next', [PlayerController::class, 'next'])->name('next');
                Route::get('previous', [PlayerController::class, 'previous'])->name('previous');

                Route::get('currently-playing', [PlayerController::class, 'currentlyPlaying'])->name('currently-playing');

                Route::group(
                    [
                        'prefix' => 'devices',
                        'as' => 'devices.',
                    ],
                    static function () {
                        Route::get('list-available', [DeviceController::class, 'listAvailable'])->name('list-available');
                        Route::get('activate/{device}/{play?}', [DeviceController::class, 'activate'])->name('activate');
                    }
                );
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'auth',
        'as' => 'auth.',
    ],
    static function () {
        Route::get('redirect', [AuthenticationController::class, 'redirect'])->name('redirect');
        Route::any('logout', [AuthenticationController::class, 'logout'])->name('logout');
        Route::get('callback', [SpotifyAuthenticationController::class, 'callback'])->name('callback');
    }
);


