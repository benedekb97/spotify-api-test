@if (
    isset($user)
    && isset($user->spotify_access_token_expiry)
    && (new \DateTime($user->spotify_access_token_expiry)) > (new \DateTime())
)
    Get data lol
@else
    <a href="{{ route('spotify.redirect') }}">Log in with spotify</a>
@endif
