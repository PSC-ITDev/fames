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
use App\Models\Activity;
use App\Models\User;

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
            $evaluation->draft_by1 = $user_id;
            $evaluation->draft_by2 = $drafters->first()?->user_id;

            $evaluation->save();
            
            
            $details = new EvaluationDetail();
            $data = [];
            $assets = FixedAsset::where('department_id',$evaluation->department_id)->where('qty','>',0)->get();
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

            $this->loggedActivity("Asset Evaluation",$evaluation->id,"Creation",$user_id);
            
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
        $users = User::all();
        $statuses = Status::all();
        $evaluation = Evaluation::with(['details','details.asset','details_writtenOff','details_remaining','creator','approved1','approved2','confirm1','confirm2','drafter2','activity'])->find($id);
        $user = Auth::user();
        $approver_ids = [$evaluation->approved_date1 ? 0 : $evaluation->approved_by1, (empty($evaluation->approved_by1) ?  0 : empty($evaluation->approved_date2)) ? $evaluation->approved_by2 : 0];
        $confirmer_ids = [$evaluation->confirmed_date1 ? 0 :  $evaluation->confirmed_by1,   (empty($evaluation->confirmed_date1) ?  0 : empty($evaluation->confirmed_date2)) ? $evaluation->confirmed_by2 : 0];
        
        $is_approver = in_array($user->id,$approver_ids);
        $is_confirmer = in_array($user->id, $confirmer_ids);

        $can_edit = $user->id == (int)$evaluation->created_by;
        // DD($user->id == (int) $evaluation->created_by, $user->id ,(int) $evaluation->created_by);
        view()->share('pageTitle', 'Evaluation Details');
        return view('fixed_assets/evaluation-details',compact('evaluation','statuses','is_approver','is_confirmer','users','can_edit'));
                
    }

    

    public function updateEvaluationDetails(Request $request,$eval_id)
    {     

        
        $evaluation = Evaluation::find($eval_id);

        // DD($request->user2);
        $user = Auth::user();

        $current_writtenOff = $evaluation->details_writtenOff;
        DD($current_writtenOff);
        // DD($request["remainingAsset"]);
        

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



            // DB::transaction(function () use ($writtenOff_data) {
            //     foreach ($writtenOff_data as $item) {
            //         FixedAsset::where('id', $item['asset_id'])
            //             ->decrement('qty', $item['writeoff_qty']);
            //     }
            // });


        //-------------------------------------------------------------------------------
        //write off
        if (!empty($request["writtenOff"])) {
            $columns = ["adwf_docno","turnover_date","adwf_date","iswrite_off","writeoff_qty","reason_for_writeoff"];

            $writtenOff_data = array_values($request["writtenOff"]);
            $writtenOff_ids = array_column($writtenOff_data, 'id');


            DB::transaction(function () use ($writtenOff_data) {
                $data =[];
                foreach ($writtenOff_data as $item) {
                    $asset = FixedAsset::lockForUpdate()->find($item['asset_id']);
                    $asset_evaluation_details =  EvaluationDetail::find($item['id']);


                    if (!$asset) {
                        continue; // or throw exception
                    }

                    $asset->qty -= $item['writeoff_qty'];
                    $asset->save();

                    // Check if qty is now 0
                    if ($asset->qty != 0) {
                        $newAsset = $asset_evaluation_details->replicate();
                        $newAsset->save();

                    }
                }
            });
            
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



        $assets_written_off = $evaluation->details_writtenOff->sum('writeoff_qty');

        $assets_on_inventory = $evaluation->details()
            ->where('iswrite_off', 0)
            ->join('fixed_assets', 'asset_evaluation_details.asset_id', '=', 'fixed_assets.id')
            ->sum('fixed_assets.qty');

        // $assets_written_off = $evaluation->details()
        //     ->where('iswrite_off', 1)
        //     ->join('fixed_assets', 'asset_evaluation_details.asset_id', '=', 'fixed_assets.id')
        //     ->sum('fixed_assets.qty');

        // DD($assets_written_off,$assets_on_inventory);

         $nextInline  = $this->hasNextInline(1,$user,$eval_id);

        
        $evaluation->update([
            'approval_status' => $nextInline['approval_status'],
            'assets_on_inventory' => $assets_on_inventory,
            'assets_written_off' => $assets_written_off,
            $nextInline['field'] => now(),
            'draft_by2' => $request->user2 ?? null,
            'approved_by1' => $request->approver_user1 ?? null,
            'approved_by2' => $request->approver_user2 ?? null,
            'confirmed_by1' => $request->confirmer_user1 ?? null,
            'confirmed_by2' => $request->confirmer_user2 ?? null
            ]);


        $this->loggedActivity("Asset Evaluation",$evaluation->id,"Submit Draft",$user->id);

        // DD($writtenOff_data);


        return redirect()->route('evaluation-details',$eval_id);
                
    }

    public function approveEvaluation($eval_id){

        $evaluation = Evaluation::find($eval_id);
        $user = Auth::user();

        $nextInline = $this->hasNextInline(2,$user,$eval_id);

        $evaluation->update([
            'approval_status' => $nextInline['approval_status'],
            $nextInline['field'] => now()
        ]);

        $this->loggedActivity("Asset Evaluation",$evaluation->id,"Approved",$user->id);

        return redirect()->route('evaluation-details',$eval_id);
    }

    public function confirmEvaluation($eval_id){
        $evaluation = Evaluation::find($eval_id);
        $user = Auth::user();

        $nextInline = $this->hasNextInline(3,$user,$eval_id);

        $evaluation->update([
            'approval_status' => $nextInline['approval_status'],
            $nextInline['field'] => now()
            ]);

            
        $this->loggedActivity("Asset Evaluation",$evaluation->id,"Confirmed",$user->id);

        return redirect()->route('evaluation-details',$eval_id);
    }

    public function rejectEvaluation(Request $request,$eval_id){
        $reason = "Rejected - ".$request->reason;
        $evaluation = Evaluation::find($eval_id);
        $evaluation->update(['approval_status' => 50]);

        $user = Auth::user();
        $this->loggedActivity("Asset Evaluation",$evaluation->id,$reason,$user->id);

        return redirect()->route('evaluation-details',$eval_id);
    }

    private function loggedActivity($type,$type_id,$activity,$performed_by){
        $log = new Activity();
        $log->type = $type;
        $log->type_id = $type_id;
        $log->activity = $activity;
        $log->performed_by = $performed_by;
        $log->save();
    }

    private function hasNextInline($type,$user,$eval_id){ ///check if approver has next inline; approver1 -> approver2

        $evaluation = Evaluation::find($eval_id);

        switch ($type) {
            case 1:
                if(!empty($evaluation->draft_by2)){


                    if(!empty($evaluation->draft_date1) && $evaluation->draft_by2 == $user->id ){
                        $approval_status = 11;//for approval
                        $field = 'draft_date2';
                    }else{
                        $approval_status = $evaluation->approval_status;//draft
                        
                        $field = 'draft_date1';
                    }

                }else{
                    $approval_status = 10;//for approval
                    $field = 'draft_date1';
                }
                break;

            case 2:

                if(!empty($evaluation->approved_by2)){

                    if(!empty($evaluation->approved_date1) && $evaluation->approved_by2 == $user->id ){
                        $approval_status = 21;//for approval
                        $field = 'approved_date2';
                    }else{
                        $approval_status = $evaluation->approval_status;
                        $field = 'approved_date1';
                    }

                    
                }else{
                    $approval_status = 20;//for approval
                    $field = 'approved_date1';
                }

                break;

            case 3:

                if(!empty($evaluation->confirmed_by2)){

                    if(!empty($evaluation->confirmed_date1) && $evaluation->confirmed_by2 == $user->id ){
                        $approval_status = 31;//for approval
                        $field = 'confirmed_date2';
                    }else{
                        $approval_status = $evaluation->approval_status;
                        $field = 'confirmed_date1';
                    }
                }else{
                    $approval_status = 30;//for approval
                    $field = 'confirmed_date1';
                }

                break;

            default:
                $approval_status = $evaluation->approval_status;
                $field = 'draft_date1';
        }

        // DD($approval_status);

        return [ 
            'approval_status' => $approval_status ,
            'field' => $field

        
        ];

    }



}


