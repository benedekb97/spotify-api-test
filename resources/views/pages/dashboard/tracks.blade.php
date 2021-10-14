@extends('layouts.dashboard')

@section('title', 'My tracks')

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>Saved Tracks</h5>
        </div>
        <div class="table-scroll">
            <table>
                <tr>
                    <th style="width:70px !important;"></th>
                    <th>Track</th>
                    <th>Duration</th>
                    <th>Playbacks</th>
                    <th>Saved at</th>
                    <th>Operations</th>
                </tr>
                <tbody id="tracks">
                <?php /** @var $track \App\Entities\Spotify\UserTrackInterface */ ?>
                @foreach($tracks as $track)
                    <tr>
                        <td>
                            <img
                                src="{{ $track->getTrack()->getAlbum()->getImages()[0]['url'] }}"
                                style="width:50px; height:50px;"
                                alt="{{ $track->getTrack()->getAlbum()->getName() }}"/>
                        </td>
                        <td>
                            <b>{{ $track->getTrack()->getName() }}</b> - {{ implode(', ', $track->getTrack()->getArtists()->map(fn (\App\Entities\Spotify\ArtistInterface $a) => $a->getName())->toArray()) }}
                        </td>
                        <td style="text-align:center;">
                            {{ date('i:s', $track->getTrack()->getDurationMs() / 1000) }}
                        </td>
                        <td style="text-align:center;">
                            {{ $track->getPlaybackCount() }}
                        </td>
                        <td style="text-align:center;">
                            {{ (new Carbon\Carbon($track->getAddedAt()))->diffForHumans() }}
                        </td>
                        <td style="font-size:20pt; text-align:center;">
                            <a data-tooltip class="top" title="Add to queue" onclick="addToQueue('{{ route('spotify.queue.add', ['uri' => $track->getTrack()->getUri()]) }}', '{{ $track->getTrack()->getName() }}');">
                                <i class="fas fa-plus-circle"></i>
                            </a>&nbsp;
                            <a data-tooltip class="top" title="Play now" onclick="playNow('{{ route('spotify.queue.add', ['uri' => $track->getTrack()->getUri()]) }}')">
                                <i class="fas fa-play-circle"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-divider" id="loading" style="display:none;">
            <p style="text-align:center; width:100%;">Loading...</p>
        </div>
    </div>
@endsection
