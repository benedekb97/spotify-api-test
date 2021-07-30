@extends('layouts.landing')

@section('title', 'Register')

@section('content')
    <div class="grid-x">
        <div class="cell small-10 small-offset-1 medium-6 medium-offset-3 large-4 large-offset-4">
            <div class="card" style="margin-top:20px;">
                <div class="card-section">
                    <div class="grid-x grid-padding-x">
                        <div class="small-12 cell">
                            <h4 class="align-center-middle">Register</h4>
                        </div>
                    </div>
                </div>
                <div class="card-section">
                    <form action="{{ route('auth.register') }}" method="post">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input value="{{ old('email') }}" name="email" class="input-group-field" required type="email" placeholder="Email address" id="email" />
                        </div>
                        <div class="input-group">
                            <input name="password" class="input-group-field" required type="password" placeholder="Password" id="password" />
                        </div>
                        <div class="input-group">
                            <input name="password_confirmation" required type="password" placeholder="Password confirmation" id="password_confirmation" />
                        </div>
                        <div class="input-group">
                            <input type="text" name="name" required placeholder="Full name" id="name" value="{{ old('name') }}" />
                        </div>

                        <div class="input-group">
                            <input type="submit" class="button" value="Register" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
