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
                <a href="{{ $item->getExternalUrl()->getSpotify() }}" target="_blank">{{ $item->getName() }}</a>
            </td>
            <td>
                <img
                    src="{{ $item->getImages()->last()->getUrl() }}"
                    style="width:100px; height:100px;"
                    alt="{{ $item->getName() }}" />
            </td>
            <td>{{ $item->getFollowers()->getTotal() }}</td>
            <td>{{ implode(', ', $item->getGenres()) }}</td>
        </tr>
    @endforeach
</table>
