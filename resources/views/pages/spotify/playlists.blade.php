@extends('layouts.dashboard')

@section('title', 'My playlists')

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>My playlists</h5>
        </div>
        <table>
            <tr>
                <th>Name</th>
                <th>Tracks</th>
            </tr>
            @foreach ($playlists as $playlist)
                <tr>
                    <td>
                        <a href="{{ route('spotify.playlists.show', ['playlist' => $playlist]) }}">
                            {{ $playlist->name }}
                        </a>
                    </td>
                    <td>
                        {{ count($playlist->playlistTracks) }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
