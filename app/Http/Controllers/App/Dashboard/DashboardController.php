<?php

namespace App\Http\Controllers\App\Dashboard;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {
         return view('layouts.dashboard.dashboard');
    }
}
