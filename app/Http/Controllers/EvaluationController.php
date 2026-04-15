<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FixedAsset;
use App\Models\Department;
use App\Models\AssetEvaluation as Evaluation;
use App\Models\AssetEvaluationDetail as EvaluationDetail;
use Illuminate\Support\Facades\Auth;

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
        $user_id = Auth::user()->id;

        $evaluation_exist = Evaluation::where([
            'quarter' => $request->input('qrt'),
            'year' => $request->input('year'),
            'department_id' => $request->input('department')
        ])->exists();
        // DD(!$evaluation_exist);

        if(!$evaluation_exist){
            $evaluation = new Evaluation();
            $evaluation->quarter = $request->input('qrt');
            $evaluation->year = $request->input('year');
            $evaluation->created_by = $user_id;
            $evaluation->department_id = $request->input('department');
            $evaluation->save();
            
            
            $details = new EvaluationDetail();
            $data = [];
            $assets = FixedAsset::where('department_id',$evaluation->department_id)->get();
            foreach($assets as $asset){
                $data[] = [
                    'asset_form_id' => $evaluation->id,
                    'asset_id' => $asset->id,
                    'iswrite_off' => 0,
                    'writeoff_qty' => 0,
                    'asset_status'=>1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $details->insert($data);
        }

        


        return redirect()->route('evaluation-details',$evaluation->id);

    }

    public function evaluationList()
    {     
        $evaluations = Evaluation::all();
        $departments = Department::all();
        $years = range(now()->year, 1900);


        view()->share('pageTitle', 'Evaluation');
        return view('fixed_assets/evaluation',compact('evaluations','departments','years'));
                
    }

    public function evaluationDetails($id)
    {     
        // $assets = FixedAsset::all();s
        $assets = EvaluationDetail::with(['asset'])->where('asset_form_id',$id)->get();
        
        view()->share('pageTitle', 'Evaluation Details');
        return view('fixed_assets/evaluation-details',compact('assets'));
                
    }

    

    




}


