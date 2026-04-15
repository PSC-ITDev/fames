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
        $asset->asset_description = $request->input('description');
        // $asset->classification = $request->input('classification');
        $asset->quantity = $request->input('quantity');
        $asset->bum = $request->input('bum');
        $asset->acquired_value = $request->input('acquired_value');
        $asset->endbookvalue = $request->input('endbookvalue');
        $asset->salvagevalue = $request->input('salvagevalue');
        $asset->usefullifeyears = $request->input('usefullifeyears');
        $asset->costcenter = $request->input('costcenter');
        $asset->save();


        // $table->id();
        // $table->integer('item');
        // $table->string('asset_no')->unique(); // Unique identifier (e.g., FA-1001)
        // $table->string('serial_number')->nullable();           
        // $table->integer('department');  // Department 
        // $table->date('capitalization_date');
        // $table->integer('qty');
        // $table->string('bum'); // Basic Unit of Measure       
        // $table->string('asset_description');            
        // $table->decimal('acquired_value', 15, 2);
        // $table->decimal('end_book_value', 15, 2);
        // $table->string('cost_center');
        // $table->string('location');
        // $table->string('other_identifier')->nullable();
        // $table->foreignId('classification_id');             
        // $table->decimal('salvage_value', 15, 2)->default(1); // Value at end of life
        // $table->integer('useful_life_years')->default(0); // For depreciation calculations
        
        // // Relationships (Convention: singular_table_id)
        // $table->foreignId('category_id'); 
        // $table->foreignId('location_id');
        
        // $table->text('notes')->nullable();


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
