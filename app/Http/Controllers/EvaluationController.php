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

        // DD($request);

        $evaluation = new Evaluation();
        $evaluation->quarter = $request->input('qrt');
        $evaluation->year = $request->input('year');
        $evaluation->save();

 
        
        return redirect()->route('evaluation');;

    }




}


