<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi
    ) {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;
    }

    public function redirect(): Response
    {
        if (Auth::check()) {
            return redirect(route('dashboard.index'));
        }

        return $this->spotifyAuthenticationApi->redirect();
    }

    public function logout(): Response
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return new RedirectResponse(route('index'));
    }
}
