@extends('layouts.dashboard')

@section('title', 'My playback history')

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>My playback history</h5>
        </div>
        <div class="table-scroll">
            <table>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Artists</th>
                    <th>Album</th>
                    <th>Played At</th>
                    <th>Operations</th>
                </tr>
                <tbody id="history">
                    @foreach ($playbacks as $playback)
                        <tr>
                            <td style="width:70px !important;">
                                <img
                                    src="{{ $playback->getTrack()->getAlbum()->getImages()[0]['url'] }}"
                                    style="width:50px; height:50px;"
                                    alt="{{ $playback->getTrack()->getAlbum()->getName() }}" />
                            </td>
                            <td>
                                {{ $playback->getTrack()->getName() }}
                            </td>
                            <td>
                                {{ implode(', ', $playback->getTrack()->getArtists()->map(fn ($a) => $a->getname())->toArray()) }}
                            </td>
                            <td>{{ $playback->getTrack()->getAlbum()->getName() }}</td>
                            <td>{{ (new \Carbon\Carbon($playback->getPlayedAt()))->diffForHumans() }}</td>
                            <td>
                                <a data-tooltip class="top" title="Add to queue" onclick="addToQueue('{{ route('spotify.queue.add', ['uri' => $playback->getTrack()->getUri()]) }}', '{{ $playback->getTrack()->getName() }}');">
                                    <i class="fas fa-plus-circle"></i>
                                </a>&nbsp;
                                <a data-tooltip class="top" title="Play now" onclick="playNow('{{ route('spotify.queue.add', ['uri' => $playback->getTrack()->getUri()]) }}')">
                                    <i class="fas fa-play-circle"></i>
                                </a>&nbsp;
                                <a data-tooltip class="top" title="Statistics" href="{{ route('spotify.tracks.show', ['track' => $playback->getTrack()->getId()]) }}">
                                    <i class="fas fa-chart-area"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
