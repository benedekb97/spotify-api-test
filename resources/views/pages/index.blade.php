<form action="{{ route('auth.login') }}" method="post">
    {{ csrf_field() }}

    <label for="email">Email address</label>
    <input name="email" required type="email" placeholder="Email address" id="email" />

    <br />
    <label for="password">Password</label>
    <input name="password" required type="password" placeholder="Password" id="password" />

    <br />
    <input type="submit" value="Log in" />
</form>

<a href="{{ route('register') }}">Register</a>
