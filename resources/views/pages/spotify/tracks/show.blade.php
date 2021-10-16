@extends('layouts.dashboard')

@section('title', $track->getName())

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>{{ $track->getName() }} &raquo; <a onclick="history.back();">Back</a></h5>
        </div>
        <div class="card-section">
            <div id="track-statistics" style="width:100%;"></div>
        </div>
        <div class="card-section">
            <div class="grid-x">
                <div class="medium-3">
                    <img alt="{{ $track->getName() }}" src="{{ $track->getAlbum()->getImages()[0]['url'] }}" />
                </div>
                <div class="medium-9" style="padding:10px;">
                    <p>Title: <b>{{ $track->getName() }}</b></p>
                    <p>Artists: <b>{!! $track->getFormattedArtistNamesWithLinks() !!}</b></p>
                    @if ($track->getAlbum() !== null)
                        <p>Album: <b><a href="{{ route('spotify.albums.show', ['album' => $track->getAlbum()->getId()]) }}">{{ $track->getAlbum()->getName() }}</a></b></p>
                    @endif
                    <p>Total playbacks: <b>{{ $track->getPlaybackCountByUser($user) }}</b></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawTrackStatistics);

        function drawTrackStatistics()
        {
            let data = google.visualization.arrayToDataTable(@json($playbacks));

            let options = {
                title: 'Playbacks by date for {{ $track->getName() }}',
                hAxis: {
                    title: 'Date',
                    titleTextStyle: {
                        color: '#000'
                    }
                },
                vAxis: {
                    minValue: 0,
                }
            }

            let chart = new google.visualization.AreaChart(document.getElementById('track-statistics'));

            chart.draw(data, options);
        }
    </script>
@endpush
