@extends('layouts.dashboard')

@section('title', $track->getName())

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>{{ $track->getName() }} &raquo; <a onclick="history.back();">Back</a></h5>
        </div>
        <div class="card-section">
            <img alt="{{ $track->getName() }}" src="{{ $track->getAlbum()->getImages()[0]['url'] }}" />
            <p>Title: <b>{{ $track->getName() }}</b></p>
            <p>Artists: <b>{{ $track->getFormattedArtistNames() }}</b></p>
            @if ($track->getAlbum() !== null)
                <p>Album: <b>{{ $track->getAlbum()->getName() }}</b></p>
            @endif
            <p>Total playbacks: <b>{{ $track->getPlaybackCountByUser($user) }}</b></p>
        </div>
        <div class="card-section">
            <div id="track-statistics" style="width:100%;"></div>
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
