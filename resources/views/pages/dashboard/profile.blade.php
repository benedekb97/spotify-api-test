@extends('layouts.dashboard')

@section('title', 'My profile')

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>My profile</h5>
        </div>
        <div class="card-section">
            <div class="grid-container fluid" style="margin-left: 0 !important; padding-left:0 !important;">
                <div class="grid-x">
                    <div class="medium-3 cell">
                        <div class="card">
                            <img src="{{ $profile->getImages()[0]['url'] }}" alt="{{ $profile->getDisplayName() }}"/>
                        </div>
                    </div>
                    <div class="medium-9 cell">
                        <div class="card" style="margin-left: 20px !important;">
                            <div class="card-divider">
                                {{ $profile->getDisplayName() }}
                            </div>
                            <div class="card-section">
                                <p><b>Country: </b>{{ $profile->getCountry() }}</p>
                                <p><b>Link: </b><a target="_blank" href="{{ $profile->getExternalUrl()['spotify'] }}">Spotify</a></p>
                                <p><b>Followers: </b>{{ $profile->getFollowers()['total'] }}</p>
                                <p><b>Product type: </b>{{ $profile->getProduct() }}</p>
                                <p><b>Tracks last updated </b>{{ ($tracksUpdatedAt = $user->getTracksUpdatedAt()) === null ? 'never' : $tracksUpdatedAt->format('Y-m-d H:i:s') }}</p>
                                <p><b>Playback history last updated </b>{{ ($playbacksUpdatedAt = $user->getPlaybacksUpdatedAt()) === null ? 'never' : $playbacksUpdatedAt->format('Y-m-d H:i:s') }}</p>
                                <p><b>Last weekly playlist: </b>{{ $user->getLastWeeklyPlaylist() === null ? 'never' : $user->getLastWeeklyPlaylist()->getCreatedAt()->format('Y-m-d') }}</p>
                                <p><b>Create weekly playlists: </b><span id="automaticallyCreateWeeklyPlaylist">{{ $user->automaticallyCreateWeeklyPlaylist() ? 'yes' : 'no' }}</span> <button class="button" style="margin-left:10px; padding:4px; margin-bottom:0;" id="toggleWeeklyPlaylists" data-user-id="{{ $user->getId() }}">Toggle</button></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
