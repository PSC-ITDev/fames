<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FixedAsset;
use App\Models\AssetEvaluation as Evaluation;

class EvaluationController extends Controller
{

    public function evaluation()
    {     
        $assets = FixedAsset::all();

        view()->share('pageTitle', 'Evaluation');
        return view('fixed_assets/evaluation',compact('assets'));
                
    }
    
    public function saveEvaluation(Request $request){

        DD($request);
        // $asset = new Evaluation();
        
        // $asset->save();

 
        
        return redirect()->route('evalutaion');;

    }




}


