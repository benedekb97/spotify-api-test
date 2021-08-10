@extends('layouts.dashboard')

@section('title', $playlist->name)

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>{{ $playlist->name }} | <a href="{{ route('spotify.playlists.index') }}">Back</a></h5>
        </div>
        <div class="table-scroll">
            <table>
                <tr>
                    <th style="width:70px !important;"></th>
                    <th>Track</th>
                    <th>Artists</th>
                    <th>Album</th>
                    <th>Duration</th>
                    <th>Operations</th>
                </tr>
                @foreach ($playlist->playlistTracks as $playlistTrack)
                    <tr>
                        <td>
                            <img
                                src="{{ $playlistTrack->track->album->images[0]['url'] }}"
                                style="width:50px !important; height:50px !important;"
                                alt="{{ $playlistTrack->track->album->name }}" />
                        </td>
                        <td>{{ $playlistTrack->track->name }}</td>
                        <td>{{ isset($playlistTrack->track->trackArtists) ? implode(', ', $playlistTrack->track->trackArtists->map(fn (\App\Models\Spotify\TrackArtist $a) => $a->artist->name)->toArray()) : '...' }}</td>
                        <td>{{ $playlistTrack->track->album->name }}</td>
                        <td>{{ date('i:s', $playlistTrack->track->duration_ms / 1000) }}</td>
                        <td>
                            <a data-tooltip class="top" title="Add to queue" onclick="addToQueue('{{ route('spotify.queue.add', ['uri' => $playlistTrack->track->uri]) }}', '{{ $playlistTrack->track->name }}');">
                                <i class="fas fa-plus-circle"></i>
                            </a>&nbsp;
                            <a data-tooltip class="top" title="Play now" onclick="playNow('{{ route('spotify.queue.add', ['uri' => $playlistTrack->track->uri]) }}')">
                                <i class="fas fa-play-circle"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>
    </div>
@endsection
