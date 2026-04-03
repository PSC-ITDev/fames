<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FixedAsset;
use App\Models\AssetEvaluation as Evaluation;
use App\Models\Department;
class EvaluationController extends Controller
{

    public function evaluation()
    {     
        $assets = FixedAsset::all();
        $departments = Department::all();
        view()->share('pageTitle', 'Evaluation');
        return view('fixed_assets/evaluation',compact('assets','departments'));
                
    }
    
    public function saveEvaluation(Request $request){

        DD($request);
        // $asset = new Evaluation();
        
        // $asset->save(); 
        
        return redirect()->route('evalutaion');;

    }




}


