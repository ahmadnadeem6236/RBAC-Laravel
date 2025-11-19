<!DOCTYPE html>
<html>
<head>
    <title>RBAC System</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        nav { background: #333; padding: 10px; }
        nav a { color: white; margin-right: 15px; text-decoration: none; }
        .content { margin-top: 20px; }
    </style>
</head>
<body>
    @auth
    <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        
        @if(auth()->user()->hasRole('project_access') || 
            auth()->user()->hasRole('manager') || 
            auth()->user()->hasRole('admin'))
            <a href="{{ route('projects.index') }}">Projects</a>
        @endif
        
        @if(auth()->user()->hasRole('manager') || 
            auth()->user()->hasRole('admin'))
            <a href="{{ route('users.index') }}">Users</a>
        @endif
        
        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.index') }}">Admin Panel</a>
        @endif
        
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
    @endauth
    
    <div class="content">
        @yield('content')
    </div>
</body>
</html>

