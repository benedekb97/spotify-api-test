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
                    src="{{ $item['album']['images'][array_key_first($item['album']['images'])]['url'] }}"
                    style="width:100px; height:100px;"
                    alt="{{ $item['album']['name'] }}" />
            </td>
            <td><a target="_blank" href="{{ $item['external_urls']['spotify'] }}">{{ $item['name'] }}</a></td>
            <td>
                {{
                    implode(', ', array_map(
                        static function ($artist): string
                        {
                            return (string)$artist['name'];
                        },
                        $item['artists']
                    ))
                }}
            </td>
            <td>{{ $item['album']['name'] }}</td>
            <td>{{ date('m:s', $item['duration_ms'] / 1000) }}</td>
        </tr>
    @endforeach
</table>
