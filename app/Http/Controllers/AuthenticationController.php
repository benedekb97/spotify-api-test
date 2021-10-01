<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Api\Authentication\SpotifyAuthenticationApi;
use App\Http\Api\Authentication\SpotifyAuthenticationApiInterface;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    private SpotifyAuthenticationApiInterface $spotifyAuthenticationApi;

    public function __construct(
        SpotifyAuthenticationApi $spotifyAuthenticationApi,
        EntityManager $entityManager
    ) {
        $this->spotifyAuthenticationApi = $spotifyAuthenticationApi;

        parent::__construct($entityManager);
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
