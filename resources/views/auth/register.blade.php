@extends('layouts.app')
@section('content')
<h2>Register</h2>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div style="margin-bottom: 10px;">
        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
        @error('name') <span style="color:red;">{{ $message }}</span> @enderror
    </div>
    <div style="margin-bottom: 10px;">
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        @error('email') <span style="color:red;">{{ $message }}</span> @enderror
    </div>
    <div style="margin-bottom: 10px;">
        <input type="password" name="password" placeholder="Password (min 6 characters)" required>
        @error('password') <span style="color:red;">{{ $message }}</span> @enderror
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: flex; align-items: center; cursor: pointer;">
            <input type="checkbox" name="remember" value="1" checked style="margin-right: 8px;">
            <span>Remember Me (Stay logged in)</span>
        </label>
    </div>
    <button type="submit" style="padding: 10px 20px; background: #2196F3; color: white; border: none; cursor: pointer; border-radius: 4px;">Register</button>
</form>
<p style="margin-top: 15px;">Already have an account? <a href="{{ route('login') }}" style="color: #4CAF50;">Login</a></p>
@endsection

