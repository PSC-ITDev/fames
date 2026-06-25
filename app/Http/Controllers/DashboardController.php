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
        $dashboard = new Dashboard();
        $dashboard->total_assets = Asset::count();
        $dashboard->new_assets = Asset::where('created_at', '>=', now()->subMonth())->count();
        $dashboard->disposed_assets = Asset::where('deleted_at', '>=', now()->subMonth())->count();
        
        $departmentsArray = $dashboard->getDepartmentsArray();
        
        $activities = Activity::whereHas('evaluation', function ($query) use ($user){
            $query->where('department_id',$user->deptid);
        })->latest()->get();
        
  

        $evaluation = Evaluation::with('details')->where('department_id',$user->deptid)->latest()->first();
        $statuses = Status::pluck('name','id');

        $doughnutData = null;
        if($evaluation){
            $grouped = $evaluation->details
                ->groupBy('asset_status')
                ->map(fn ($items) => $items->count());

            $doughnutData = collect($statuses)->mapWithKeys(function ($label, $key) use ($grouped) {
                return [
                    $label => $grouped[$key] ?? 0
                ];
            })->toArray();


            $currentYear = now()->year;
            $previousYear = now()->subYear()->year;
            $years = [$currentYear, $previousYear];
            $data = Evaluation::whereIn('year', $years)->where('department_id',$user->deptid)->get();

            $barData = $data->map(function ($evaluation) use ($statuses) {
                $grouped = $evaluation->details
                    ->groupBy('asset_status')
                    ->map->count();

                return [
                    'name' =>  $evaluation->quarter .' '. $evaluation->year,
                    'statusData' => collect($statuses)
                        ->mapWithKeys(fn ($label, $key) => [
                            $label => $grouped->get($key, 0),
                        ])
                        ->toArray(),
                ];
            });
            
        // DD($barData);
        }

        view()->share('pageTitle', 'Dashboard');
        return view('dashboard',compact('dashboard', 'departmentsArray','activities','evaluation','doughnutData','barData'));

    }
    




}


