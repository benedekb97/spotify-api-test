<title>My Top {{ $type }}</title>

<table>
    <tr>
        <th>#</th>
        <th>Image</th>
        <th>Title</th>
        <th>Artists</th>
        <th>Album</th>
        <th>Duration</th>
    </tr>
    @foreach ($items as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                <img
                    src="{{ $item->getAlbum()->getImages()->first()->getUrl() }}"
                    style="width:100px; height:100px;"
                    alt="{{ $item->getAlbum()->getName() }}" />
            </td>
            <td><a target="_blank" href="{{ $item->getExternalUrl()->getSpotify() }}">{{ $item->getName() }}</a></td>
            <td>
                {{
                    implode(', ', $item->getArtists()->map(
                        static function (\App\Http\Api\Responses\ResponseBodies\Entity\Artist $artist) {
                            return $artist->getName();
                        }
                    )->toArray())
                }}
            </td>
            <td>{{ $item->getAlbum()->getName() }}</td>
            <td>{{ date('m:s', $item->getDurationms() / 1000) }}</td>
        </tr>
    @endforeach
</table>
