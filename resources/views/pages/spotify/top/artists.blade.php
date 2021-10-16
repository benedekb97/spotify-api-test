@extends('layouts.dashboard')

@section('title', "My top $type")

@section('content')
    <div class="card">
        <div class="card-divider">
            <h5>My top artists</h5>
        </div>
        <div class="table-scroll">
            <table>
                <tr>
                    <th style="width:70px !important;"></th>
                    <th>Name</th>
                    <th>Followers</th>
                    <th>Genres</th>
                </tr>
                @foreach ($items as $key => $item)
                    <tr>
                        <td>
                            <img
                                src="{{ $item->getImages()->last()->getUrl() }}"
                                style="width:64px; height:64px;"
                                alt="{{ $item->getName() }}" />
                        </td>
                        <td>
                            <a href="{{ $item->getExternalUrl()->getSpotify() }}" target="_blank">{{ $item->getName() }}</a>
                        </td>
                        <td style="text-align:right;">{{ number_format($item->getFollowers()->getTotal()) }}</td>
                        <td style="text-align:center;">{{ implode(', ', $item->getGenres()) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
