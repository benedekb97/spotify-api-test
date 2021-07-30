@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-divider">
            Recommended songs |&nbsp;<a href="#" onclick="updateRecommendations()">Refresh</a>
        </div>
        <table style="border:1px solid rgba(0, 0, 0, 0.2);">
            <tr>
                <th>Image</th>
                <th>Artist</th>
                <th>Title</th>
                <th>Operations</th>
            </tr>
            <tbody id="recommendations">

            </tbody>
        </table>
    </div>
@endsection
