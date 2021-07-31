@extends('layouts.dashboard')

@section('title', 'My listening history')

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>My playback history</h5>
        </div>
        <table>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Artists</th>
                <th>Album</th>
                <th>Played At</th>
                <th>Operations</th>
            </tr>
            <tbody id="history">
                @foreach ($playbacks as $playback)
                    <tr>
                        <td>
                            <img
                                src="{{ $playback->track->album->images[0]['url'] }}"
                                style="width:50px; height:50px;"
                                alt="{{ $playback->track->album->name }}" />
                        </td>
                        <td>
                            {{ $playback->track->name }}
                        </td>
                        <td>
                            {{ implode(', ', $playback->track->trackArtists->map(fn ($a) => $a->artist->name)->toArray()) }}
                        </td>
                        <td>{{ $playback->track->album->name }}</td>
                        <td>{{ (new \Carbon\Carbon($playback->played_at))->diffForHumans() }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
