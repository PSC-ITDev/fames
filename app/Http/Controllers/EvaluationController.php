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
use App\Models\Attachment;

use App\Mail\EvaluationActivityMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        $qMap = [
            '1st' => 1,
            '2nd' => 2,
            '3rd' => 3,
            '4th' => 4,
        ];

        $reverseMap = [
            1 => '1st',
            2 => '2nd',
            3 => '3rd',
            4 => '4th',
        ];

        $currentQtr = $qMap[$request->input('qrt')] ?? null;

        $prevQtr = $currentQtr === 1 ? 4 : $currentQtr - 1;

        $prevQtrLabel = $reverseMap[$prevQtr];

        
        $prev_eval = Evaluation::where([
            'quarter' => $prevQtrLabel,
            'year' => (string) ($prevQtr == 4 ? ((int)$request->input('year') - 1) : $request->input('year')),
            'department_id' => $request->input('department')
        ])->first();

        
        
        // dd($request);

        $user_id = Auth::user()->id;

        $evaluation_exist = Evaluation::where([
            'quarter' => $request->input('qrt'),
            'year' => $request->input('year'),
            'department_id' => $request->input('department')
        ])->exists();

        $deptid = $request->input('department');
        $department =  Department::find($deptid);
        $default_status = Status::where('name','Operational In Good Condition')->first()->id;


        // DD(!$evaluation_exist);
        // $hierarchy = ApprovalHierarchy::where('deptid',$request->input('department'))->get();
        // $drafters = $hierarchy->where('type',1);
        // $approvers = $hierarchy->where('type',2);
        // $confirmers = $hierarchy->where('type',3);


        // DD($approvers,$confirmers);

        if(!$evaluation_exist){
            $evaluation = new Evaluation();
            $evaluation->quarter = $request->input('qrt');
            $evaluation->year = $request->input('year');
            $evaluation->created_by = $user_id;
            $evaluation->department_id = $request->input('department');

            $evaluation->draft_by1 = $user_id;
            $evaluation->draft_by2 = $department->preparedby2;
            
            $evaluation->confirmed_by1 = $department->confirmed1;
            $evaluation->confirmed_by2 = $department->confirmed2;
            
            $evaluation->approved_by1 = $department->approved1;
            $evaluation->approved_by2 = $department->approved2;
           
            
            $evaluation->save();

        ////chunk
            if($prev_eval){
                $assets = $prev_eval->details->where('qty', '>', 0)->where('iswrite_off',0);

                //new_assets created before the evaluation creation
                $new_assets = FixedAsset::where('department_id', $evaluation->department_id)
                    ->where('qty', '>', 0)
                    ->where(function ($query) use ($evaluation,$prev_eval) {
                        $query->where('created_at','<=',$evaluation->created_at)
                            ->where('created_at','>=',$prev_eval->created_at);
                    })
                    ->get();
            }else{

                $assets = FixedAsset::where('department_id', $evaluation->department_id)
                ->where('qty', '>', 0)
                ->get();

                $new_assets = null;
            }

            $data = [];
            foreach($assets as $asset) {
                if($asset->qty == 0){continue;}
                $data[] = [
                    'asset_form_id' => $evaluation->id,
                    'asset_id'      => $prev_eval ? $asset->asset_id : $asset->id,
                    'iswrite_off'   => $asset->iswrite_off ?? 0,
                    'writeoff_qty'  => $asset->writeoff_qty ?? 0,
                    'asset_status'  => $asset->asset_status ?? $default_status, // get the last asset status
                    'created_at'    => now(),
                    'updated_at'    => now(),
                    'qty'           => $asset->qty
                    
                ];
            }

            if($new_assets){
                foreach($new_assets as $asset) {
                    if($asset->qty == 0){continue;}
                    $data[] = [
                        'asset_form_id' => $evaluation->id,
                        'asset_id'      => $asset->id,
                        'iswrite_off'   => 0,
                        'writeoff_qty'  => 0,
                        'asset_status'  => $default_status, // get the last asset status
                        'created_at'    => now(),
                        'updated_at'    => now(),
                        'qty'           => $asset->qty
                        
                    ];
                }
            }

            // Break the array into chunks of 200 rows to stay well below the 2100 limit
            foreach (array_chunk($data, 200) as $chunk) {
                EvaluationDetail::insert($chunk);
            }
        //end chunk


            $this->loggedActivity("Asset Evaluation",$evaluation->id,"Creation",$user_id,'');
            
            return redirect()->route('evaluation-details',$evaluation->id);
        }else{

            return redirect()->route('evaluation-list');
        }


    }

    public function evaluationList()
    {    
        $user = Auth::user();
        if(in_array(strtolower($user->role->name),['user','approver'])){
            $evaluations = Evaluation::withSum('details', 'qty')->orderBy('created_at', 'desc')->where('department_id',$user->deptid)->get();
        }elseif(strtolower($user->role->name) == 'accounting'){
            $evaluations = Evaluation::withSum('details', 'qty')
            ->orderBy('created_at', 'desc')
            ->where('department_id',$user->deptid)
            ->whereHas('details', function ($query) {
                $query->where('iswrite_off',1);
            }, '>=', 1)
            ->get();

        }else{
            $evaluations = Evaluation::withSum('details', 'qty')->orderBy('created_at', 'desc')->where('approval_status','>=',10)->get();
        }
        $departments = Department::all();
        $years = range(now()->year, 1900);
        $user->load('department');

        view()->share('pageTitle', 'Evaluation');
        return view('fixed_assets/evaluation',compact('evaluations','departments','years','user'));
                
    }

    public function evaluationDetails($id)
    {     
        $user = Auth::user();
        $users = User::whereNot('id',$user->id)->get();
        $statuses = Status::all();
        $evaluation = Evaluation::with(['details','details.asset','details_writtenOff','details_remaining','creator','approved1','approved2','confirm1','confirm2','drafter2','activity' ])->find($id);
        

        
        $approver_ids = [$evaluation->approved_date1 ? 0 : $evaluation->approved_by1, (empty($evaluation->approved_by1) ?  0 : empty($evaluation->approved_date2)) ? $evaluation->approved_by2 : 0];
        $confirmer_ids = [$evaluation->confirmed_date1 ? 0 :  $evaluation->confirmed_by1,  empty($evaluation->confirmed_date2) ? $evaluation->confirmed_by2 : 0];
        
        $is_approver = in_array($user->id,$approver_ids);
        $is_confirmer = in_array($user->id, $confirmer_ids);
        $is_accounting = strtolower($user->role->name) == "accounting";

        $can_edit = $user->id == (int)$evaluation->created_by;
        
        $notdraft = $evaluation->approval_status > 0 ? true : false; 
        $is_owner = $evaluation->created_by == $user->id ? true : false; 

        $asset_count = $evaluation->details->sum('qty');

        // DD($asset_count);
        // DD($is_confirmer,$confirmer_ids,(empty($evaluation->confirmed_date1) ?  0 : empty($evaluation->confirmed_date2)) ? $evaluation->confirmed_by2 : 0);
        // DD($user->id == (int) $evaluation->created_by, $user->id ,(int) $evaluation->created_by);

        view()->share('pageTitle', 'Evaluation Details');
        return view('fixed_assets/evaluation-details',compact('evaluation','statuses','is_approver','is_confirmer','users','user','can_edit','notdraft','is_owner','asset_count','is_accounting'));
                
    }

    

    public function updateEvaluationDetails(Request $request,$eval_id)
    {     
        // DD($request);
        
        $evaluation = Evaluation::find($eval_id);

        // DD($request->user2);
        $user = Auth::user();

        $current_writtenOff = $evaluation->details_writtenOff;
        
        
        //DD($current_writtenOff);
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
                if(isset($row['id'])){
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
                    if ($asset->qty != 0) 
                    {
                        $check_qty = $asset_evaluation_details->qty - $item['writeoff_qty'];
                        if($check_qty != 0){
                            
                            $asset_evaluation_details->update(['qty' => $check_qty]);
                            $newAsset = $asset_evaluation_details->replicate();
                            $newAsset->save();
                        }else{
                            $asset_evaluation_details->update(['iswrite_off'=>true]);
                        }
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


      
        
        $evaluation->update([
            'assets_on_inventory' => $assets_on_inventory,
            'assets_written_off' => $assets_written_off,
            'draft_by2' => $request->user2 ?? null,
            'approved_by1' => $request->approver_user1 ?? null,
            'approved_by2' => $request->approver_user2 ?? null,
            'confirmed_by1' => $request->confirmer_user1 ?? null,
            'confirmed_by2' => $request->confirmer_user2 ?? null,
        ]);

            
        $nextInline  = $this->hasNextInline(1,$user,$eval_id);

        $evaluation->update([
            'approval_status' => $nextInline['approval_status'],
            $nextInline['field'] => now(),
        ]);


        $this->loggedActivity("Asset Evaluation",$evaluation->id,"Submit for Approval",$user->id,'');

        $nextInlineUser = User::find($nextInline['approver']);
        $approverName = $nextInlineUser->name;
        $email = $nextInlineUser->email;
        $subject = "Evaluation Submitted";
        $requestTitle = 'Evaluation '.$evaluation->year .' '.$evaluation->quarter.' Qtr.';
        $requestorName = $user->name;
        $url = url('evaluationdetails/'.$eval_id);
        $this->sendMail($email,$subject, $approverName,$requestTitle,$requestorName,$url, $evaluation->creator->email,$evaluation->approval_status  );

         

        return redirect()->route('evaluation-details',$eval_id);
                
    }

    public function checkEvaluation(Request $request,$eval_id){
        $evaluation = Evaluation::find($eval_id);
        $user = Auth::user();

        $nextInline = $this->hasNextInline(1,$user,$eval_id);
        $evaluation->update([
            'approval_status' => $nextInline['approval_status'],
            $nextInline['field'] => now()
        ]);
        $reason = $request->reason;
        $this->loggedActivity("Asset Evaluation",$evaluation->id,"Submit for Approval",$user->id,$reason);

        $nextInlineUser = User::find($nextInline['approver']);
        $approverName = $nextInlineUser->name;
        $email = $nextInlineUser->email;
        $subject = "Evaluation for Approval";
        $requestTitle = $evaluation->year.' Evaluation '.$evaluation->quarter.' Qtr.';
        $requestorName = $user->name;
        $url = url('evaluationdetails/'.$eval_id);
        $this->sendMail($email,$subject, $approverName,$requestTitle,$requestorName,$url, $evaluation->creator->email,$evaluation->approval_status );


        return redirect()->route('evaluation-details',$eval_id);
    }

    public function approveEvaluation(Request $request,$eval_id){

        $evaluation = Evaluation::find($eval_id);
        $user = Auth::user();
        $url = url('evaluationdetails/'.$eval_id);
        $requestorName = $user->name;
        $requestTitle = 'Evaluation '.$evaluation->year .' '.$evaluation->quarter.' Qtr.';
        $subject = "Evaluation for Approval";


        if($request->reviewer){
            $evaluation->update([
                'review_date' => now(),
                'review_by' => $user->id
            ]);

            $reason = $request->reason;
            $this->loggedActivity("Asset Evaluation",$evaluation->id,"Accounting Approved",$user->id,$reason);

            $nextInline = $this->hasNextInline(6,$user,$eval_id);
            $nextInlineUser = User::find($nextInline['approver']);
            $approverName = $nextInlineUser->name;
            $email = $nextInlineUser->email;

            $this->sendMail($email,$subject, $approverName,$requestTitle,$requestorName,$url, $evaluation->creator->email,$evaluation->approval_status  );

        }else{

            $nextInline = $this->hasNextInline(2,$user,$eval_id);

            $evaluation->update([
                'approval_status' => $nextInline['approval_status'],
                $nextInline['field'] => now()
            ]);

            $reason = $request->reason;
            $this->loggedActivity("Asset Evaluation",$evaluation->id,"Approved",$user->id,$reason);
            
            $nextInlineUser = User::find($nextInline['approver']);
            $approverName = $nextInlineUser->name;
            $email = $nextInlineUser->email;

            $this->sendMail($email,$subject, $approverName,$requestTitle,$requestorName,$url, $evaluation->creator->email,$evaluation->approval_status  );

            
        }
        return redirect()->route('evaluation-details',$eval_id);
    }

    public function confirmEvaluation(Request $request,$eval_id){
        $evaluation = Evaluation::find($eval_id);
        $user = Auth::user();

        $nextInline = $this->hasNextInline(3,$user,$eval_id);

        $evaluation->update([
            'approval_status' => $nextInline['approval_status'],
            $nextInline['field'] => now()
        ]);

        $reason = $request->reason;
            
        $this->loggedActivity("Asset Evaluation",$evaluation->id,"Confirmed",$user->id,$reason);

        $nextInlineUser = User::find($nextInline['approver']);
        $approverName = $nextInlineUser->name;
        $email = $nextInlineUser->email;
        $subject = "Evaluation to Recieve";
        $requestTitle = 'Evaluation '.$evaluation->year .' '.$evaluation->quarter.' Qtr.';
        $requestorName = $user->name;
        $url = url('evaluationdetails/'.$eval_id);
        $this->sendMail($email,$subject, $approverName,$requestTitle,$requestorName,$url, $evaluation->creator->email,$evaluation->approval_status  );

        

        return redirect()->route('evaluation-details',$eval_id);
    }

    public function rejectEvaluation(Request $request,$eval_id){
        $reason = $request->reason;
        $evaluation = Evaluation::find($eval_id);
        $user = Auth::user();
        $subject = "Evaluation Rejected";
        $requestTitle = 'Evaluation '.$evaluation->year .' '.$evaluation->quarter.' Qtr.';
        $requestorName = $user->name;
        $url = url('evaluationdetails/'.$eval_id);

        $toCC = Activity::where('type_id',$evaluation->id)
            ->where('performed_by', '!=', $user->id)
            ->where('activity', 'like', '%Approved%')
            ->distinct()
            ->get()
            ->pluck('performer.email')
            ->unique();

        if($request->reviewer){
            $evaluation->update([
                'review_date' => now(),
                'review_by' => $user->id
            ]);

            $reason = $request->reason;
            $this->loggedActivity("Asset Evaluation",$evaluation->id,"Rejected by Accounting",$user->id,$reason);

            $nextInline = $this->hasNextInline(6,$user,$eval_id);
            $nextInlineUser = User::find($nextInline['approver']);
            $approverName = $nextInlineUser->name;
            $email = $nextInlineUser->email;

            $this->sendMail($email,$subject, $approverName,$requestTitle,$requestorName,$url, $evaluation->creator->email,$evaluation->approval_status, $toCC  );


        }else{
            $nextInline = $this->hasNextInline(5,$user,$eval_id);
            $evaluation->update([
                'approval_status' => $nextInline['approval_status'],
                $nextInline['field'] => now()
            ]);

            $this->loggedActivity("Asset Evaluation",$evaluation->id,"Rejected",$user->id,$reason);

            $nextInlineUser = User::find($nextInline['approver']);
            $approverName = $nextInlineUser->name;
            $email = $nextInlineUser->email;


            $this->sendMail($email,$subject, $approverName,$requestTitle,$requestorName,$url, $evaluation->creator->email,$evaluation->approval_status, $toCC );


        }
        return redirect()->route('evaluation-details',$eval_id);
    }

    public function editEvaluation(Request $request,$eval_id){
        $evaluation = Evaluation::find($eval_id);

        $evaluation->update([
            'confirmed_date1' => null,
            'confirmed_date2' => null,
            'approved_date1' => null,
            'approved_date2' => null,
            'draft_date1' => null,
            'draft_date2' => null,
            'approval_status'=> 0,
        ]);  

        return redirect()->route('evaluation-details',$eval_id);

    }





    public function splitStatus(Request $request,$eval_detail_id){
        $eval_detail = EvaluationDetail::find($eval_detail_id);
        $statuses = $request->status;
        $detail_records = EvaluationDetail::where(['asset_form_id' => $eval_detail->asset_form_id,'asset_id'=> $eval_detail->asset_id])->get();

        $data = [];
            foreach($statuses as $key => $status) {
                $detail = $detail_records->where('asset_status',$key)->first();
                // DD($key,$status,$detail);
                if($detail){
                    $diff = $detail->qty - (int)$status;
                    if($diff < 0 ||($detail->id === $eval_detail->id && is_null($status))){
                        $detail->delete();
                    }else{
                        if(!(is_null($status))){
                            $detail->increment('qty',(int)$status); 
                            
                        }
                        if(($detail->id === $eval_detail->id)){
                            
                            $detail->update(['qty' => (int)$status]);
                        }
                    }
                }else{
                    if(!(is_null($status))){

                        $data[] = [
                            'asset_form_id' => $eval_detail->asset_form_id,
                            'asset_id'      => $eval_detail->asset_id,
                            'iswrite_off'   => $eval_detail->iswrite_off,
                            'writeoff_qty'  => $eval_detail->writeoff_qty,
                            'asset_status'  => $key, // get the last asset status
                            'created_at'    => now(),
                            'updated_at'    => now(),
                            'qty' => $status ?? 1
                        ];
                    }
                }
            }
        EvaluationDetail::insert($data);

        return redirect()->route('evaluation-details',$eval_detail->asset_form_id);
    }





    private function loggedActivity($type,$type_id,$activity,$performed_by,$reason){
        $log = new Activity();
        $log->type = $type;
        $log->type_id = $type_id;
        $log->activity = $activity;
        $log->performed_by = $performed_by;
        $log->reason = $reason;
        $log->save();
    }

    private function hasNextInline($type,$user,$eval_id){ ///check if approver has next inline; approver1 -> approver2

        $evaluation = Evaluation::find($eval_id);

        switch ($type) {
            case 1:
                if(!empty($evaluation->draft_by2)){

                    
                    // DD(!empty($evaluation->draft_by2));
                    if(!empty($evaluation->draft_date1) && $evaluation->draft_by2 == $user->id ){
                        $approval_status = 11;//for approval
                        $field = 'draft_date2';
                        $approver = $evaluation->approved_by1;
                    }else{
                        $approval_status = 1;//draft
                        $field = 'draft_date1';
                        $approver = $evaluation->draft_by2;
                    }
                
                }else{
                    $approval_status = 10;//for approval
                    $field = 'draft_date1';
                    $approver = $evaluation->approved_by1;
                }
                break;

            case 2:

                if(!empty($evaluation->approved_by2)){

                    if(!empty($evaluation->approved_date1) && $evaluation->approved_by2 == $user->id ){
                        $approval_status = 21;//for approval
                        $field = 'approved_date2';
                        $approver = $evaluation->confirmed_by1;
                    }else{
                        $approval_status = $evaluation->approval_status;
                        $field = 'approved_date1';
                        $approver = $evaluation->approved_by2;
                    }

                    
                }else{
                    $approval_status = 20;//for approval
                    $field = 'approved_date1';
                    $approver = $evaluation->confirmed_by1;
                }

                break;

            case 3:

                if(!empty($evaluation->confirmed_by2)){

                    if(!empty($evaluation->confirmed_date1) && $evaluation->confirmed_by2 == $user->id ){
                        $approval_status = 31;//for approval
                        $field = 'confirmed_date2';
                        $approver = $evaluation->created_by;
                    }else{
                        $approval_status = $evaluation->approval_status;
                        $field = 'confirmed_date1';
                        $approver = $evaluation->confirmed_by2;
                    }
                }else{
                    $approval_status = 30;//for approval
                    $field = 'confirmed_date1';
                    $approver = $evaluation->created_by;
                }

                break;    

            case 5:

                $matchedColumn = collect($evaluation->getAttributes())
                ->filter(fn ($value, $key) => in_array($key, [
                    'approved_by1',
                    'approved_date1',
                    'approved_by2',
                    'approved_date2',
                    'confirmed_by1',
                    'confirmed_date1',
                    'confirmed_by2',
                    'confirmed_date2',
                    'draft_by2',
                    'draft_date2',
                ]))
                ->search($user->id);

                switch ($matchedColumn) {
                    case 'approved_by1':
                        $field = 'approved_date1';

                        if(!empty($evaluation->approved_by2)){
                            $approver = $evaluation->approved_by2;
                        }else{
                            $approver = $evaluation->confirmed_by1;
                        }
                        break;
                    case 'approved_by2':
                        $field = 'approved_date2';
                        $approver = $evaluation->confirmed_by1;
                        break;
                    case 'confirmed_by1':
                        $field = 'confirmed_date1';
                        if(!empty($evaluation->confirmed_by2)){
                            $approver = $evaluation->confirmed_by2;
                        }else{
                            $approver = $evaluation->draft_by1;
                        }
                        break;
                    case 'confirmed_by2':
                        $field = 'confirmed_date2';
                        $approver = $evaluation->draft_by1;
                        break;
                    case 'draft_by1':
                        $field = 'draft_date1';
                        if(!empty($evaluation->draft_by2)){
                            $approver = $evaluation->draft_by2;
                        }else{
                            $approver = $evaluation->approved_by1;
                        }
                        break;
                    case 'draft_by2':
                        $field = 'draft_date2';
                        $approver = $evaluation->approved_by1;
                        break;

                    default:
                        $field = 'draft_date1';
                        $approver = $evaluation->draft_by1;
                }


                $approval_status = 50;

                break;

            case 6:
                    $approval_status = $evaluation->approval_status;//for approval
                    $field = '';
                    $approver = $evaluation->confirmed_by1;
                break;
            default:
                $approval_status = $evaluation->approval_status;
                $field = 'draft_date1';
                $approver = $evaluation->draft_by1;
        }

        // DD($approval_status);

        return [ 
            'approval_status' => $approval_status ,
            'field' => $field,
            'approver' => $approver

        
        ];

    }

    private function sendMail($email,$subject, $approverName,$requestTitle,$requestorName,$url ,$owner_email,$approval_status,$toCC=[]){
        

        $approval_statuses = [
            0 => 'Pending',
            10 => 'For Approval',
            20 => 'Approved',
            30 => 'Confirmed',
            50 => 'Rejected'
        ];

            
        $status = $approval_statuses[floor($approval_status / 10) * 10];
        $ccEmails = collect([$owner_email, $toCC])
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->toArray();
            
        Mail::to($email)
        ->cc($ccEmails)
        ->send(
            new EvaluationActivityMail(
                $subject,
                $approverName,
                $requestTitle,
                $requestorName,
                $url,
                $status
                
            )
        );
    }




    public function upload(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|integer',
            'file' => 'required|file|max:10240',
        ]);

        $evaluation = Evaluation::findOrFail($request->evaluation_id);

        $file = $request->file('file');

        if (!$file || !$file->isValid()) {
            return response()->json(['message' => 'Invalid file'], 422);
        }

        $fileHash = md5_file($file->getPathname());

        $exists = Attachment::where('attachable_id', $evaluation->id)
        ->where('file_hash', $fileHash)->first();

        if ($exists) {
            if($request->replace){
                
                // delete old physical file
                Storage::disk('public')->delete($exists->file_path);

                // overwrite DB record instead of creating new one
                $filename = $file->getClientOriginalName();

                $path = $file->storeAs(
                    "uploads/evaluations/{$evaluation->id}",
                    $filename,
                    'public'
                );

                $exists->update([
                    'file_name' => $filename,
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'file_hash' => $fileHash,
                ]);

                return response()->json([
                    'status' => 'replaced',
                    'message' => 'File replaced successfully',
                    'data' => $exists
                ]);

            }

        

            return response()->json([
                'status' => 'duplicate',
                'message' => 'File already exists',
                'existing' => $exists
            ]);
        }

        if (!$evaluation->id) {
            return response()->json(['message' => 'Invalid evaluation ID'], 422);
        }

        $directory = 'uploads/evaluations/' . (int) $evaluation->id . '/';

        $filename = $file->getClientOriginalName();

        $path = $file->storeAs($directory, $filename, 'public');

        // Save to database
        $attachment = $evaluation->attachments()->create([
            'file_name' => $filename,
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'file_hash' => $fileHash,
        ]);

        return response()->json([
            'status' => 'uploaded',
            'message' => 'Uploaded successfully',
            'attachment' => $attachment
        ]);
    }

    public function remove_file(Request $request,$file_id){

        $exists = Attachment::findOrFail($file_id);
            
        if($exists){
            // delete old physical file
            Storage::disk('public')->delete($exists->file_path);

            $exists->delete();

            return response()->json([
                'status' => 'deleted',
                'message' => 'File Deleted successfully'
            ]);
        }
       
    }
    
    public function fileList(Request $request,$eval_id)
    {
        $evaluation = Evaluation::findOrFail($eval_id);
        return response()->json([
            'status' => 'Success',
            'data' => $evaluation->attachments,
        ]);
    }
}
