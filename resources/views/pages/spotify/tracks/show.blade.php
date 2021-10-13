@extends('layouts.dashboard')

@section('title', $track->getName())

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>{{ $track->getName() }}</h5>
        </div>
{{--        <img alt="{{ $track->getName() }}" src="{{ $track->getAlbum()->getImages()[0]['url'] }}" />--}}
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
