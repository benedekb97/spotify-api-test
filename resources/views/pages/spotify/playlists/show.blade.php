@extends('layouts.dashboard')

@section('title', $playlist->getName())

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>{{ $playlist->getName() }} | <a href="{{ route('spotify.playlists.index') }}">Back</a></h5>
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
                @foreach ($playlist->getPlaylistTracks() as $playlistTrack)
                    <tr>
                        <td>
                            <img
                                src="{{ $playlistTrack->getTrack()->getAlbum()->getImages()[0]['url'] }}"
                                style="width:50px !important; height:50px !important;"
                                alt="{{ $playlistTrack->getTrack()->getAlbum()->getName() }}" />
                        </td>
                        <td>{{ $playlistTrack->getTrack()->getName() }}</td>
                        <td>{{ !$playlistTrack->getTrack()->getArtists()->isEmpty() ? implode(', ', $playlistTrack->getTrack()->getArtists()->map(fn (\App\Entities\Spotify\ArtistInterface $a) => $a->getName())->toArray()) : '...' }}</td>
                        <td>{{ $playlistTrack->getTrack()->getAlbum()->getName() }}</td>
                        <td>{{ date('i:s', $playlistTrack->getTrack()->getDurationms() / 1000) }}</td>
                        <td>
                            <a data-tooltip class="top" title="Add to queue" onclick="addToQueue('{{ route('spotify.queue.add', ['uri' => $playlistTrack->getTrack()->getUri()]) }}', '{{ $playlistTrack->getTrack()->getName() }}');">
                                <i class="fas fa-plus-circle"></i>
                            </a>&nbsp;
                            <a data-tooltip class="top" title="Play now" onclick="playNow('{{ route('spotify.queue.add', ['uri' => $playlistTrack->getTrack()->getUri()]) }}')">
                                <i class="fas fa-play-circle"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>
    </div>
@endsection
