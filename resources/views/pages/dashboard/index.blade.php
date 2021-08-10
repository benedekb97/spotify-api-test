@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>Recommended songs |&nbsp;<a onclick="updateRecommendations()">Refresh</a></h5>
        </div>
        <div class="table-scroll">
            <table style="border:1px solid rgba(0, 0, 0, 0.2);">
                <tr>
                    <th style="width:70px !important;"></th>
                    <th>Artist</th>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Operations</th>
                </tr>
                <tbody id="recommendations">

                </tbody>
            </table>
        </div>
    </div>
@endsection
