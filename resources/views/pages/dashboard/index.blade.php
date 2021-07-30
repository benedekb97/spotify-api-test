@if (
    isset($user)
    && isset($user->spotify_access_token_expiry)
    && (new \DateTime($user->spotify_access_token_expiry)) > (new \DateTime())
)
    Get data lol<br>
    <a href="{{ route('spotify.profile') }}">Get user profile data</a><br>
    <a href="{{ route('spotify.top', ['type' => 'tracks']) }}">Get top tracks</a><br>
    <a href="{{ route('spotify.top', ['type' => 'artists']) }}">Get top artists</a><br>

    @isset($currentlyPlaying)
        Currently playing:<br>
        {{ implode(', ', $currentlyPlaying->getItem()->getArtists()->map(
            static function (\App\Http\Api\Responses\ResponseBodies\Entity\Artist $artist) {
                return $artist->getName();
            }
        )->toArray()) }}
        - {{ $currentlyPlaying->getItem()->getName() }}
    @endisset
@else
    <a href="{{ route('spotify.redirect') }}">Log in with spotify</a>
@endif
