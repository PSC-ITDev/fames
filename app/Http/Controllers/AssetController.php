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


//     <!-- item
// asset_no
// serial_number
// capitalization_date
// qty
// bun	
// asset_description	
// acquired_value	
// end_book_value	
// cost_center	
// location
// other_identifier	
// classification_id
// salvage_value
// useful_life_years
// category_id	bigint
// location_id	bigint
// notes -->

}
