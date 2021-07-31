<!DOCTYPE html>
<html lang="hu">
<head>
    <title>@yield('title', 'Spotify Playground')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://kit.fontawesome.com/492a2c0a6b.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="top-bar">
    <div class="top-bar-left">
        <ul class="menu">
            <li class="menu-text">Spotify Playground</li>
            @if (\Auth::user()->isLoggedInWithSpotify())
                <li>
                    <a href="{{ route('dashboard.index') }}"><b>Dashboard</b></a>
                </li>
                <li>
                    <a href="{{ route('spotify.top', ['type' => 'tracks']) }}">Top Tracks</a>
                </li>
                <li>
                    <a href="{{ route('spotify.top', ['type' => 'artists']) }}">Top Artists</a>
                </li>
                <li>
                    <a href="{{ route('dashboard.history') }}">History</a>
                </li>
            @endif
        </ul>
    </div>
    <div class="top-bar-right">
        <ul class="menu">
            <li>
                <a href="{{ route('spotify.profile') }}">My profile</a>
            </li>
        </ul>
    </div>
</div>
<div class="grid-container fluid">
    @if(!\Auth::user()->isLoggedInWithSpotify())
        <div class="grid-x">
            <div class="cell medium-6 medium-offset-3 large-4 large-offset-4" style="margin-top:20px;">
                <div class="input-group align-center-middle">
                    <a class="button" href="{{ route('spotify.redirect') }}">Log in with spotify!</a>
                </div>
            </div>
        </div>
    @else
        <div class="grid-x grid-padding-x" style="margin-top:20px;">
            <div class="medium-3 cell">
                <div class="card">
                    <div class="card-divider">
                        <h5>Currently playing</h5>
                    </div>
                        <img src=""
                             alt=""
                             id="currentlyPlayingImg"
                        />
                        <div class="card-section">
                            <div class="grid-x">
                                <div class="cell small-2" style="font-size:20pt;">
                                    <a href="#" onclick="moveTrack('{{ route('spotify.previous') }}')">
                                        <i class="fas fa-angle-double-left"></i>
                                    </a>
                                </div>
                                <div class="cell small-8 align-center-middle" style="text-align:center;">
                                    <span id="currentlyPlayingText" class="align-center-middle"></span>
                                </div>
                                <div class="cell small-2" style="text-align:right; font-size:20pt;">
                                    <a href="#" onclick="moveTrack('{{ route('spotify.next') }}')">
                                        <i class="fas fa-angle-double-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>

                <div id="callouts">

                </div>
            </div>
            <div class="medium-9 cell">
                @yield('content')
            </div>
        </div>
    @endif
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
