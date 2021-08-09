@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>Recommended songs |&nbsp;<a onclick="updateRecommendations()">Refresh</a></h5>
        </div>
        <table style="border:1px solid rgba(0, 0, 0, 0.2);">
            <tr>
                <th>Image</th>
                <th>Artist</th>
                <th>Title</th>
                <th>Duration</th>
                <th>Operations</th>
            </tr>
            <tbody id="recommendations">

            </tbody>
        </table>
    </div>
@endsection
