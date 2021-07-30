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
<br><br>
    @isset($recommendations)
<b>Recommendations:</b><br>
        <table style="border:1px solid rgba(0, 0, 0, 0.2);">
            <tr>
                <th>Image</th>
                <th>Artist</th>
                <th>Title</th>
                <th>Operations</th>
            </tr>
            @foreach($recommendations->getTracks() as $track)
                <tr>
                    <td>
                        <img src="{{ $track->getAlbum()->getImages()->first()->getUrl() }}"
                             style="width:50px; height:50px;"
                             alt="{{ $track->getAlbum()->getName() }}" />
                    </td>
                    <td>{{ implode(', ', $track->getArtists()->map(fn(\App\Http\Api\Responses\ResponseBodies\Entity\Artist $artist) => $artist->getName())->toArray()) }}</td>
                    <td>{{ $track->getName() }}</td>
                    <td>
                        <a href="#" onclick="addToQueue('{{ route('spotify.queue.add', ['uri' => $track->getUri()]) }}')">Add to queue</a>
                    </td>
                </tr>
            @endforeach
        </table>
    @endisset
@else
    <a href="{{ route('spotify.redirect') }}">Log in with spotify</a>
@endif



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function addToQueue(url)
    {
        $.ajax({
            url: url
        });
    }
</script>
