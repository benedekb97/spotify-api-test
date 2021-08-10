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
                                    src="{{ $playback->track->album->images[0]['url'] }}"
                                    style="width:50px; height:50px;"
                                    alt="{{ $playback->track->album->name }}" />
                            </td>
                            <td>
                                {{ $playback->track->name }}
                            </td>
                            <td>
                                {{ implode(', ', $playback->track->trackArtists->map(fn ($a) => $a->artist->name)->toArray()) }}
                            </td>
                            <td>{{ $playback->track->album->name }}</td>
                            <td>{{ (new \Carbon\Carbon($playback->played_at))->diffForHumans() }}</td>
                            <td>
                                <a data-tooltip class="top" title="Add to queue" onclick="addToQueue('{{ route('spotify.queue.add', ['uri' => $playback->track->uri]) }}', '{{ $playback->track->name }}');">
                                    <i class="fas fa-plus-circle"></i>
                                </a>&nbsp;
                                <a data-tooltip class="top" title="Play now" onclick="playNow('{{ route('spotify.queue.add', ['uri' => $playback->track->uri]) }}')">
                                    <i class="fas fa-play-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
