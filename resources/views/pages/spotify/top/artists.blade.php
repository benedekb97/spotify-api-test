<title>My Top {{ $type }}</title>

<table>
    <tr>
        <th>Name</th>
        <th>Image</th>
        <th>Followers</th>
        <th>Genres</th>
    </tr>
    @foreach ($items as $key => $item)
        <tr>
            <td>
                <a href="{{ $item['external_urls']['spotify'] }}" target="_blank">{{ $item['name'] }}</a>
            </td>
            <td>
                <img
                    src="{{ $item['images'][array_key_first($item['images'])]['url'] }}"
                    style="width:100px; height:100px;"
                    alt="{{ $item['name'] }}" />
            </td>
            <td>{{ $item['followers']['total'] }}</td>
            <td>{{ implode(', ', $item['genres']) }}</td>
        </tr>
    @endforeach
</table>
