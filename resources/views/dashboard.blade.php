@extends('layouts.app')
@section('content')
<h1>Welcome, {{ auth()->user()->name }}!</h1>
<div style="margin-top: 20px;">
    <h3>Your Access:</h3>
    <ul>
        @if(auth()->user()->hasRole('project_access'))
            <li>✅ Projects</li>
        @endif
        
        @if(auth()->user()->hasRole('manager'))
            <li>✅ Projects</li>
            <li>✅ User Management</li>
        @endif
        
        @if(auth()->user()->hasRole('admin'))
            <li>✅ Full Admin Access (All Features)</li>
        @endif
    </ul>
</div>
<div style="margin-top: 30px;">
    @if(auth()->user()->hasRole('project_access') || 
        auth()->user()->hasRole('manager') || 
        auth()->user()->hasRole('admin'))
        <a href="{{ route('projects.index') }}" style="padding: 10px; background: blue; color: white; text-decoration: none;">Go to Projects</a>
    @endif
    
    @if(auth()->user()->hasRole('manager') || 
        auth()->user()->hasRole('admin'))
        <a href="{{ route('users.index') }}" style="padding: 10px; background: green; color: white; text-decoration: none;">Manage Users</a>
    @endif
    
    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.index') }}" style="padding: 10px; background: red; color: white; text-decoration: none;">Admin Panel</a>
    @endif
</div>
@endsection

