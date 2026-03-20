<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function newasset(Request $request)
    {     
        // return view('assets.index', compact(''));
        return view('assets.create');
    }
}
