<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FixedAsset;
use App\Models\Department;
use App\Models\AssetClassification as Classification;
use App\Models\AssetLocation as Location;
use App\Models\AssetCategory as Category;
use App\Models\ApprovalHierarchy;
use App\Models\User;


class MasterListController extends Controller
{
    //ASSETS
    public function assetList(Request $request)
    { 
        $assets = FixedAsset::with(['department','classification'])->get();
        $departments = Department::all();
        $classifications = Classification::all();

       view()->share('pageTitle', 'Asset List');
        return view('master_list/assets/list', compact(['assets','departments','classifications'])); 
    }
    
    public function saveAsset(Request $request){
        $asset = new FixedAsset();
        $asset->asset_no = $request->input('asset_no');
        $asset->item = $request->input('item');
        $asset->serial_number = $request->input('serial_number');
        $asset->capitalization_date = $request->input('capitalization_date');
        $asset->location = $request->input('location');
        $asset->other_identifier = $request->input('other_identifier');
        $asset->category_id = $request->input('category_id');
        $asset->location_id = $request->input('location_id');
        $asset->department_id = $request->input('department');
        $asset->asset_description = $request->input('description');
        $asset->classification_id = $request->input('classification');
        $asset->qty = $request->input('quantity');
        $asset->bum = $request->input('bum');
        $asset->acquired_value = $request->input('acquired_value');
        $asset->end_book_value = $request->input('endbookvalue');
        $asset->salvage_value = $request->input('salvagevalue');
        $asset->useful_life_years = $request->input('usefullifeyears');
        $asset->cost_center = $request->input('costcenter');
        $asset->save();

 
        
        return redirect()->route('asset-list');;

    }


    // DEPARTMENTS
    public function departmentList(Request $request)
    { 
        $departments = Department::all();

       view()->share('pageTitle', 'Department List');
        return view('master_list/departments/list', compact('departments')); 
    }
    
    public function saveDepartment(Request $request){
        $department = new Department();
        $department->code = $request->input('code');
        $department->name = $request->input('name');
        $department->description = $request->input('description');
        $department->save();

        return redirect()->route('department-list');

    }

    public function viewDepartment(Request $request,$deptid){
        $users = User::all();
        $department = Department::with(['hierarchy','drafter','approver','confirmer'])->find($deptid);

        view()->share('pageTitle', 'Department '. $department->name);
        return view('master_list/departments/view', compact('department','users')); 
    }

    // LOCATIONS
    public function locationList(Request $request)
    { 
        $locations = Location::all();

       view()->share('pageTitle', 'Location List');
        return view('master_list/locations/list', compact('locations')); 
    }
    
    public function saveLocation(Request $request){
        $location = new Location();
        $location->name = $request->input('name');
        $location->description = $request->input('description');
        $location->save();
        
        return redirect()->route('location-list');;

    }


    // CLASSIFICATION
    public function classificationList(Request $request)
    { 
        $classifications = Classification::all();

       view()->share('pageTitle', 'Classification List');
        return view('master_list/classifications/list', compact('classifications')); 
    }
    
    public function saveClassification(Request $request){
        $classification = new Classification();
 
        $classification->name = $request->input('name');
        $classification->description = $request->input('description');
        $classification->save();
        
        return redirect()->route('classification-list');;

    }



    // CATEGORY
    public function categoryList(Request $request)
    { 
        $categories = Category::all();

       view()->share('pageTitle', 'Category List');
        return view('master_list/categories/list', compact('categories')); 
    }
    
    public function saveCategory(Request $request){
        $category = new Category();
 
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->save();
        
        return redirect()->route('category-list');;

    }

    // HIERARCHY
    public function hierarchyList(Request $request)
    { 
        $hierarchies = ApprovalHierarchy::with(['user','approver_user','confirmer_user'])->get();
        $users = User::all();
        $departments = Department::all();

       view()->share('pageTitle', 'Hierarchy List');
        return view('master_list/hierarchy/list', compact('hierarchies','users','departments')); 
    }
    
    public function saveHierarchy(Request $request,$deptid){
        
        $data = [];
        foreach($request->hierarchy as $index => $users){
            foreach($users as $user){
                $index = trim($index, "'\"");
                if($user){
                    $data[] = [
                        'user_id' => $user,
                        'type'  => $index == 'approver_user' ? 2 : ($index == 'confirmer_user' ?  3 : 1),
                        'deptid'   => $deptid,
                    ];
                }
            }
        }

        $data = collect($data)
            ->unique(fn ($row) => $row['deptid'].'-'.$row['type'].'-'.$row['user_id'])
            ->values()
            ->toArray();
        // DD($data);
        ApprovalHierarchy::upsert($data, ['deptid','type','user_id'],[]);
        
        return redirect()->route('view-department',$deptid);

    }


    
        // Route::post('/save-hierarchy',[MasterListController::class, 'saveHierarchy']) ->name('savehierarchy');
        // Route::get('/hierarchy-list', [MasterListController::class, 'hierarchyList'])->name('hierarchy-list');

}


