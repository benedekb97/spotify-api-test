@if (
    isset($user)
    && isset($user->spotify_access_token_expiry)
    && (new \DateTime($user->spotify_access_token_expiry)) > (new \DateTime())
)
    Get data lol<br>
    <a href="{{ route('spotify.profile') }}">Get user profile data</a><br>
    <a href="{{ route('spotify.top', ['type' => 'tracks']) }}">Get top tracks</a><br>
    <a href="{{ route('spotify.top', ['type' => 'artists']) }}">Get top artists</a>
@else
    <a href="{{ route('spotify.redirect') }}">Log in with spotify</a>
@endif
