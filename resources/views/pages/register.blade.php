<form action="{{ route('auth.register') }}" method="post">
    {{ csrf_field() }}

    <label for="email">Email address</label>
    <input name="email" required type="email" placeholder="Email address" id="email" value="{{ old('email') }}" />

    <br />
    <br />
    <label for="password">Password</label>
    <input name="password" required type="password" placeholder="Password" id="password" />

    <br />
    <br />
    <label for="password_confirmation">Password confirmation</label>
    <input name="password_confirmation" required type="password" placeholder="Password confirmation" id="password_confirmation" />

    <br />
    <br />
    <label for="name">Full name</label>
    <input name="name" required placeholder="Full name" id="name" value="{{ old('name') }}" />

    <input type="submit" value="Register" />
</form>
