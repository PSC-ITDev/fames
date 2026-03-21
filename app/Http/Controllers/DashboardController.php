<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
      public function index(Request $request)
    {     
        // return view('assets.index', compact(''));

        view()->share('pageTitle', 'Dashboard');
        return view('dashboard');
                
    }
}
