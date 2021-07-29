<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request): Response
    {
        $login = Auth::attempt(
            [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]
        );

        if ($login) {
            return new RedirectResponse(route('index'));
        }

        return new JsonResponse(
            [
                'error' => 'Incorrect credentials.'
            ]
        );
    }

    public function register(RegistrationRequest $request): Response
    {
        $user = new User();

        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->name = $request->get('name');

        $user->save();

        Auth::login($user);

        return new RedirectResponse(route('dashboard.index'));
    }

    public function logout(): Response
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return new RedirectResponse(route('index'));
    }
}
