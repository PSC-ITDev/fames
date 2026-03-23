<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FixedAsset;

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
        $asset = new FixedAsset();
        $asset->description = $request->input('description');
        $asset->classification = $request->input('classification');
        $asset->quantity = $request->input('quantity');
        $asset->bum = $request->input('bum');
        $asset->acquired_value = $request->input('acquired_value');
        $asset->endbookvalue = $request->input('endbookvalue');
        $asset->salvagevalue = $request->input('salvagevalue');
        $asset->usefullifeyears = $request->input('usefullifeyears');
        $asset->costcenter = $request->input('costcenter');
        $asset->save();

 
        view()->share('pageTitle', 'Asset Enrollment');
        return view('assets.create');

    }

}
