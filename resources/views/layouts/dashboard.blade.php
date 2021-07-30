<!DOCTYPE html>
<html lang="hu">
<head>
    <title>@yield('title', 'Spotify Playground')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
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
            <div class="cell small-10 small-offset-1 medium-6 medium-offset-3 large-4 large-offset-4" style="margin-top:20px;">
                <div class="input-group align-center-middle">
                    <a class="button" href="{{ route('spotify.redirect') }}">Log in with spotify!</a>
                </div>
            </div>
        </div>
    @else
        <div class="grid-x grid-padding-x" style="margin-top:20px;">
            <div class="small-3 cell">
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
                                <div class="cell small-2" style="">
                                    <a href="#" onclick="moveTrack('{{ route('spotify.previous') }}')">&#8612;</a>
                                </div>
                                <div class="cell small-8 align-center-middle" style="text-align:center;">
                                    <span id="currentlyPlayingText" class="align-center-middle"></span>
                                </div>
                                <div class="cell small-2" style="text-align:right;">
                                    <a href="#" onclick="moveTrack('{{ route('spotify.next') }}')">&#8614;</a>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="small-9 cell">
                @yield('content')
            </div>
        </div>
    @endif
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
