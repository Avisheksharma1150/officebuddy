<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
{
    $this->middleware(function ($request, $next) {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/home')->with('error', 'Access denied.');
        }
        return $next($request);
    });
}
}
