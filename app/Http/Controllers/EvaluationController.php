<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FixedAsset;
use App\Models\Department;
use App\Models\AssetEvaluation as Evaluation;
use App\Models\AssetEvaluationDetail as EvaluationDetail;
use App\Models\AssetStatus as Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ApprovalHierarchy;

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

        // DD($request);
        $user_id = Auth::user()->id;

        $evaluation_exist = Evaluation::where([
            'quarter' => $request->input('qrt'),
            'year' => $request->input('year'),
            'department_id' => $request->input('department')
        ])->exists();
        // DD(!$evaluation_exist);
        $hierarchy = ApprovalHierarchy::where('deptid',$request->input('department'))->get();
        $drafters = $hierarchy->where('type',1);
        $approvers = $hierarchy->where('type',2);
        $confirmers = $hierarchy->where('type',3);


        // DD($approvers,$confirmers);

        if(!$evaluation_exist){
            $evaluation = new Evaluation();
            $evaluation->quarter = $request->input('qrt');
            $evaluation->year = $request->input('year');
            $evaluation->created_by = $user_id;
            $evaluation->department_id = $request->input('department');

            $evaluation->approved_by1 = $approvers->first()?->user_id;
            $evaluation->approved_by2 = $approvers->skip(1)->first()?->user_id;
            $evaluation->confirmed_by1 = $confirmers->first()?->user_id;
            $evaluation->confirmed_by2 = $confirmers->skip(1)->first()?->user_id;
            $evaluation->draft_by2 = $drafters->first()?->user_id;

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
            return redirect()->route('evaluation-details',$evaluation->id);
        }else{

            return redirect()->route('evaluation-list');
        }

        



    }

    public function evaluationList()
    {     
        $evaluations = Evaluation::all();
        $departments = Department::all();
        $years = range(now()->year, 1900);
        $user = Auth::user();
        $user->load('department');

        view()->share('pageTitle', 'Evaluation');
        return view('fixed_assets/evaluation',compact('evaluations','departments','years','user'));
                
    }

    public function evaluationDetails($id)
    {     
        
        $statuses = Status::all();
        $evaluation = Evaluation::with(['details','details.asset','details_writtenOff','details_remaining','creator','creator.hierarchy'])->find($id);
        $user = Auth::user();
        $approver_ids = $evaluation->department->approver->pluck('user_id')->toArray();
        $confirmer_ids = $evaluation->department->confirmer->pluck('user_id')->toArray();

        $is_approver = in_array($user->id,$approver_ids);
        $is_confirmer = in_array($user->id, $confirmer_ids);

        view()->share('pageTitle', 'Evaluation Details');
        return view('fixed_assets/evaluation-details',compact('evaluation','statuses','is_approver','is_confirmer'));
                
    }

    

    public function updateEvaluationDetails(Request $request,$eval_id)
    {     
            // DD(array_values($request["remainingAsset"]));

        if (!empty($request["remainingAsset"])) {
            $columns = ["asset_status","corrective_actiion_taken","iswrite_off"];

            // $evaluation = Evaluation::with(['details'])->find($id);
            $remainingAsset_data = array_values($request["remainingAsset"]);
            $remainingAsset_ids = array_column($remainingAsset_data, 'id');
            
            $cases = [];
            foreach ($columns as $col) {
                $cases[$col] = "CASE id ";
            }
            foreach ($remainingAsset_data as $row) {

                $id = (int) $row['id'];

                foreach ($columns as $col) {

                    $value = $row[$col] ?? null;

                    if (is_null($value)) {
                        $cases[$col] .= "WHEN $id THEN NULL ";
                    } else {

                        // escape string safely
                        $value = addslashes($value);

                        $cases[$col] .= "WHEN $id THEN '$value' ";
                    }
                }
            }

            foreach ($columns as $col) {
                $cases[$col] .= "ELSE $col END";
            }
            $set = collect($cases)
                ->map(fn($case, $col) => "$col = $case")
                ->implode(', ');

            DB::statement("
                UPDATE asset_evaluation_details
                SET $set
                WHERE id IN (" . implode(',', array_map('intval', $remainingAsset_ids)) . ")
            ");
        }

        // DD($remainingAsset_data);


        //-------------------------------------------------------------------------------
        //write off
        if (!empty($request["writtenOff"])) {
            $columns = ["adwf_docno","turnover_date","adwf_date","iswrite_off","writeoff_qty","reason_for_writeoff"];

            $writtenOff_data = array_values($request["writtenOff"]);
            $writtenOff_ids = array_column($writtenOff_data, 'id');
            $cases = [];

            foreach ($columns as $col) {
                $cases[$col] = "CASE id ";
            }
        
            foreach ($writtenOff_data as $row) {

                $id = (int) $row['id'];

                foreach ($columns as $col) {

                    $value = $row[$col] ?? null;

                    if (is_null($value)) {
                        $cases[$col] .= "WHEN $id THEN NULL ";
                    } else {

                        // escape string safely
                        $value = addslashes($value);

                        $cases[$col] .= "WHEN $id THEN '$value' ";
                    }
                }
            }

            foreach ($columns as $col) {
                $cases[$col] .= "ELSE $col END";
            }

            $set = collect($cases)
                ->map(fn($case, $col) => "$col = $case")
                ->implode(', ');

            DB::statement("
                UPDATE asset_evaluation_details
                SET $set
                WHERE id IN (" . implode(',', array_map('intval', $writtenOff_ids)) . ")
            ");
        }


        $evaluation = Evaluation::find($eval_id);
        
        $evaluation->update([
            'approval_status' => 1,
            'assets_on_inventory' => $evaluation->details_writtenOff->count(),
            'assets_written_off' => $evaluation->details_remaining->count(),
            ]);


        // DD($writtenOff_data);


        return redirect()->route('evaluation-list');
                
    }

    public function approveEvaluation($eval_id){

        $evaluation = Evaluation::find($eval_id);
        
        $user = Auth::user();

        $evaluation->update([
            'approval_status' => 2,
            'approved_by1' => $user->id,
            'approved_date1' => now()
            ]);

        return redirect()->route('evaluation-details',$eval_id);
    }

    public function confirmEvaluation($eval_id){
        $evaluation = Evaluation::find($eval_id);
        $user = Auth::user();
        $evaluation->update([
            'approval_status' => 3,
            'confirmed_by1' => $user->id,
            'confirmed_date1' => now()]);
        return redirect()->route('evaluation-details',$eval_id);
    }

    public function rejectEvaluation($eval_id){
        $evaluation = Evaluation::find($eval_id);
        $evaluation->update(['approval_status' => 4]);
        return redirect()->route('evaluation-details',$eval_id);
    }

    




}


