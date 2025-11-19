@extends('layouts.app')
@section('content')
<h2>Login</h2>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div style="margin-bottom: 10px;">
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        @error('email') <span style="color:red;">{{ $message }}</span> @enderror
    </div>
    <div style="margin-bottom: 10px;">
        <input type="password" name="password" placeholder="Password" required>
        @error('password') <span style="color:red;">{{ $message }}</span> @enderror
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: flex; align-items: center; cursor: pointer;">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} style="margin-right: 8px;">
            <span>Remember Me (Stay logged in)</span>
        </label>
    </div>
    <button type="submit" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 4px;">Login</button>
</form>
<p style="margin-top: 15px;">Don't have an account? <a href="{{ route('register') }}" style="color: #2196F3;">Register</a></p>
@endsection

