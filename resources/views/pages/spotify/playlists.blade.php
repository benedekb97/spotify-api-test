@extends('layouts.dashboard')

@section('title', 'My playlists')

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>My playlists</h5>
        </div>
        <div class="table-scroll">
            <table>
                <tr>
                    <th style="width:70px !important;"></th>
                    <th>Name</th>
                    <th>Tracks</th>
                </tr>
                @foreach ($playlists as $playlist)
                    <tr>
                        <td>
                            @if (!empty($playlist->getImages()) && array_key_exists('url', $image = Arr::first($playlist->getImages())))
                                <img
                                    src="{{ $image['url'] }}"
                                    style="width:50px; height:50px;"
                                    alt="{{ $playlist->getName() }}" />
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('spotify.playlists.show', ['playlist' => $playlist->getId()]) }}">
                                {{ $playlist->getName() }}
                            </a>
                        </td>
                        <td>
                            {{ count($playlist->getPlaylistTracks()) }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
