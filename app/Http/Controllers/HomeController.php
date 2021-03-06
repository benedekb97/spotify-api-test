<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return new RedirectResponse(route('dashboard.index'));
        }

        return view('pages.index');
    }
}
