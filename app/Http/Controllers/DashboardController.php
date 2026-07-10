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
<<<<<<< Updated upstream

=======
        $dashboard = new Dashboard();
        $dashboard->total_assets = Asset::count();
        $dashboard->new_assets = Asset::where('created_at', '>=', now()->subMonth())->count();
        $dashboard->disposed_assets = Asset::where('deleted_at', '>=', now()->subMonth())->count();

        
        $currentYear = now()->year;
        $previousYear = now()->subYear()->year;
        $years = [$currentYear, $previousYear];
        
        $departmentsArray = $dashboard->getDepartmentsArray();
>>>>>>> Stashed changes
        
        $activities = Activity::whereHas('evaluation', function ($query) use ($user){
            $query->where('department_id',$user->deptid);
        })->latest()->get();
        
  

<<<<<<< Updated upstream
        $evaluation = Evaluation::with('details')
        ->where('department_id',$user->deptid)
        ->whereIn('approval_status',[30,31])
        ->orderByDesc('year')
        ->orderByDesc('quarter')->first();
=======
        $evaluation = Evaluation::with('details')->where('department_id',$user->deptid)->where('year',$currentYear)->latest()->first();
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
            $currentYear = now()->year;
            $previousYear = now()->subYear()->year;
            $years = [$currentYear, $previousYear];
            $data = Evaluation::whereIn('year', $years)
                ->where('department_id',$user->deptid)
                ->whereIn('approval_status',[30,31])
                ->orderByDesc('year')
                ->orderByDesc('quarter')
                ->get();
=======
            $data = Evaluation::whereIn('year', $years)->where('department_id',$user->deptid)->get();
>>>>>>> Stashed changes

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
        return view('dashboard',compact('activities','evaluation','doughnutData','barData'));

    }
    




}


