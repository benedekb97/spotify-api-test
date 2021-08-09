@extends('layouts.dashboard')


@section('title', "My top $type")

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>Top played songs</h5>
        </div>
        <table>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Artists</th>
                <th>Album</th>
                <th>Duration</th>
                <th>Operations</th>
            </tr>
            @foreach ($items as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <img
                            src="{{ $item->getAlbum()->getImages()->first()->getUrl() }}"
                            style="width:50px; height:50px;"
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
                    <td>{{ date('i:s', $item->getDurationms() / 1000) }}</td>
                    <td style="font-size:20pt;">
                        <a data-tooltip class="top" title="Add to queue" onclick="addToQueue('{{ route('spotify.queue.add', ['uri' => $item->getUri()]) }}', '{{ $item->getName() }}');">
                            <i class="fas fa-plus-circle"></i>
                        </a>&nbsp;
                        <a data-tooltip class="top" title="Play now" onclick="playNow('{{ route('spotify.queue.add', ['uri' => $item->getUri()]) }}')">
                            <i class="fas fa-play-circle"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
