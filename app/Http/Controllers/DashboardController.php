<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dashboard as Dashboard;
use App\Models\FixedAsset as Asset;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // return view('assets.index', compact(''));

        $dashboard = new Dashboard();
        // Here you would typically fetch and calculate the necessary data for the dashboard
        // For example:
        $dashboard->total_assets = Asset::count();
        $dashboard->new_assets = Asset::where('created_at', '>=', now()->subMonth())->count();
        $dashboard->disposed_assets = Asset::where('deleted_at', '>=', now()->subMonth())->count();
        
        $departmentsArray = $dashboard->getDepartmentsArray();


        view()->share('pageTitle', 'Dashboard');
        return view('dashboard',compact('dashboard', 'departmentsArray'));

    }
    




}


