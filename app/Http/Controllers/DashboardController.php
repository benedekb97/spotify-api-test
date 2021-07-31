<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Spotify\Playback;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.index');
    }

    public function history()
    {
        $playbacks = Playback::where('user_id', Auth::id())->orderBy('played_at', 'desc')->get();

        return view(
            'pages.dashboard.history',
            [
                'playbacks' => $playbacks
            ]
        );
    }
}
