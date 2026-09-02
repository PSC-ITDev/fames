<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dashboard as Dashboard;
use App\Models\FixedAsset as Asset;
use App\Models\Activity;
use App\Models\AssetEvaluation as Evaluation;
use App\Models\AssetStatus as Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // return view('assets.index', compact(''));
        $user = Auth::user();
        
        $activities = Activity::whereHas('evaluation', function ($query) use ($user){
            $query->where('department_id',$user->deptid);
        })->latest()->get();
        
  
        if($user?->role->name === 'Auditor'){
            $evaluation = Evaluation::with('details')
            ->whereIn('approval_status',[30,31])
            ->orderByDesc('year')
            ->orderByDesc('quarter')->first();
        }else{
            $evaluation = Evaluation::with('details')
            ->where('department_id',$user->deptid)
            ->whereIn('approval_status',[30,31])
            ->orderByDesc('year')
            ->orderByDesc('quarter')->first();
        }


        $statuses = Status::pluck('name','id');

        $doughnutData = null;
        $barData = null;
        if($evaluation){
            $writeoffCount =  $evaluation->details->where('iswrite_off', 1)->sum('writeoff_qty');
            $grouped = $evaluation->details->where('iswrite_off', 0)
                ->groupBy('asset_status')
                ->map(fn ($items) => $items->count());

            $doughnutData = collect($statuses)->mapWithKeys(function ($label, $key) use ($grouped) {
                return [
                    $label => $grouped[$key] ?? 0
                ];
            })->toArray();

            $doughnutData['Write Off'] = $writeoffCount;

            $currentYear = now()->year;
            $previousYear = now()->subYear()->year;
            $years = [$currentYear, $previousYear];
            $data = Evaluation::whereIn('year', $years)
                ->where('department_id',$user->deptid)
                ->whereIn('approval_status',[30,31])
                ->orderByDesc('year')
                ->orderByDesc('quarter')
                ->get();

            $barData = $data->map(function ($evaluation) use ($statuses) {
                $writeoffCount =  $evaluation->details->where('iswrite_off', 1)->sum('writeoff_qty');
                $grouped = $evaluation->details->where('iswrite_off', 0)
                    ->groupBy('asset_status')
                    ->map->count();

                $statusData = collect($statuses)
                        ->mapWithKeys(fn ($label, $key) => [
                            $label => $grouped->get($key, 0),
                        ])
                        ->toArray();
                $statusData['Write Off'] = $writeoffCount;

                return [
                    'name' =>  $evaluation->quarter .' '. $evaluation->year,
                    'statusData' => $statusData,
                ];
            });
            
        // DD($barData);
        }

        view()->share('pageTitle', 'Dashboard');
        return view('dashboard',compact('activities','evaluation','doughnutData','barData','user'));

    }
    




}


