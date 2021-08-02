@extends('layouts.landing')

@section('content')
    <div class="grid-y height-50 align-center-middle" style="height:800px;">
        <div class="cell">
            <div class="grid-x">
                <div class="cell medium-6 medium-offset-3 large-4 large-offset-4">
                    <div class="card" style="margin-top:20px;">
                        <div class="card-section" style="text-align:center;">
                            <a href="{{ route('auth.redirect') }}">
                                <img width="50%" src="{{ asset('img/connect.png') }}" alt="connect">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
