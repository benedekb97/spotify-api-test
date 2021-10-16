@extends('layouts.dashboard')

@section('title', $artist->getName())

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>{{ $artist->getName() }} &raquo; <a onclick="history.back();">Back</a></h5>
        </div>
        <div class="table-scroll">
            <table>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Playbacks</th>
                    <th>Duration</th>
                    <th>Operations</th>
                </tr>
                @php
                    $i = 1;
                @endphp
                @foreach ($tracks as $track)
                    <tr>
                        <td style="text-align:center;">{{ $i++ }}</td>
                        <td>{{ $track->getName() }}</td>
                        <td style="text-align:center;">{{ $track->getPlaybackCountByUser($user) }}</td>
                        <td style="text-align:center;">{{ $track->getFormattedDuration() }}</td>
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
