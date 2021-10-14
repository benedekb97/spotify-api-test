@extends('layouts.dashboard')

@section('title', $album->getName())

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>{{ $album->getName() }} &raquo; <a onclick="history.back()">Back</a></h5>
        </div>
        <div class="table-scroll">
            <table>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Playbacks</th>
                    <th>Operations</th>
                </tr>
                @foreach ($album->getSortedTracks() as $track)
                    <tr style="background-color:rgba(0, 255, 0, {{ $statistics->getTrackStatisticsById($track->getId())->getAlpha() }});">
                        <td style="text-align:center;">{{ $track->getTrackNumber() }}</td>
                        <td>{{ $track->getName() }}</td>
                        <td style="text-align:center;">{{ $track->getFormattedDuration() }}</td>
                        <td style="text-align:center;">{{ $statistics->getTrackStatisticsById($track->getId())->getPlaybacks() }}</td>
                        <td style="font-size:12pt; text-align:center;">
                            <a data-tooltip class="top" title="Add to queue" onclick="addToQueue('{{ route('spotify.queue.add', ['uri' => $track->getUri()]) }}', '{{ $track->getName() }}');">
                                <i class="fas fa-plus-circle"></i>
                            </a>&nbsp;
                            <a data-tooltip class="top" title="Play now" onclick="playNow('{{ route('spotify.queue.add', ['uri' => $track->getUri()]) }}')">
                                <i class="fas fa-play-circle"></i>
                            </a>&nbsp;
                            <a data-tooltip class="top" title="Statistics" href="{{ route('spotify.tracks.show', ['track' => $track->getId()]) }}">
                                <i class="fas fa-chart-area"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
