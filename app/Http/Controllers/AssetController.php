<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function newasset(Request $request)
    {     
        // return view('assets.index', compact(''));

        view()->share('pageTitle', 'Asset Enrollment');
        return view('assets.create');
                
    }

    public function saveasset(Request $request)
    {
        view()->share('pageTitle', 'Asset Enrollment');
        return view('assets.list');

    }

}
