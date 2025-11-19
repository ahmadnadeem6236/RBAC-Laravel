<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('project_access') && 
            !auth()->user()->hasRole('manager') && 
            !auth()->user()->hasRole('admin')) {
            abort(403, 'Access Denied');
        }
        
        return view('projects.index');
    }
}
