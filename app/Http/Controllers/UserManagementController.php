<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('manager') && 
            !auth()->user()->hasRole('admin')) {
            abort(403, 'Access Denied');
        }
        
        return view('users.index');
    }
}
