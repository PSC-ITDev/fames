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
use App\Models\Role;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Gate;


class MasterListController extends Controller
{

    //ASSETS
    public function assetList(Request $request)
    { 
        Gate::any(['auditor', 'superadmin']);
        $assets = FixedAsset::with(['department','classification'])->get();
        $departments = Department::all();
        $classifications = Classification::all();
        $categories = Category::all();
        $locations = Location::all();


        $grouped = $assets->groupBy('category_id')
                ->map(fn ($items) => $items->count());
        $assetData = collect($categories->pluck('name','id'))->mapWithKeys(function ($label, $key) use ($grouped) {
            return [
                $label => $grouped[$key] ?? 0
            ];
        })->toArray();
       view()->share('pageTitle', 'Asset List');
        return view('master_list/assets/list', compact(['assets','departments','classifications','categories','locations','assetData'])); 
    }
    
    public function saveAsset(Request $request){
        Gate::any(['auditor', 'superadmin']);
        $asset = new FixedAsset();
        $asset->asset_number = $request->input('asset_no');
        // $asset->item = $request->input('item');
        // $asset->serial_number = $request->input('serial_number');
        $asset->capitalization_date = $request->input('capitalization_date');
        // $asset->location = $request->input('location');
        $asset->other_identifier = $request->input('other_identifier');
        $asset->category_id = $request->input('category_id');
        $asset->location_id = $request->input('location_id');
        $asset->department_id = $request->input('department');
        $asset->asset_description = $request->input('asset_description');
        $asset->classification_id = $request->input('classification');
        $asset->qty = $request->input('quantity');
        $asset->bun = $request->input('bum');
        // $asset->acquired_value = $request->input('acquired_value');
        $asset->end_book_val = $request->input('endbookvalue');
        $asset->salvage_value = $request->input('salvagevalue');
        $asset->useful_life_years = $request->input('usefullifeyears');
        $asset->cost_center_id = $request->input('costcenter');
        $asset->ordinary_depreciation_start_date = $request->input('ordinary_depreciation_start_date');
        $asset->save();

 
        
        return redirect()->route('asset-list');

    }


    // DEPARTMENTS
    public function departmentList(Request $request)
    { 
        Gate::authorize('superadmin');
        $departments = Department::all();

       view()->share('pageTitle', 'Department List');
        return view('master_list/departments/list', compact('departments')); 
    }
    
    public function saveDepartment(Request $request){
        Gate::authorize('superadmin');
        $department = new Department();
        $department->code = $request->input('code');
        $department->name = $request->input('name');
        $department->description = $request->input('description');
        $department->save();

        return redirect()->route('department-list');

    }

    public function viewDepartment(Request $request,$deptid){
        Gate::authorize('superadmin');
        $users = User::all();
        $department = Department::with(['hierarchy','drafter','approver','confirmer'])->find($deptid);

        view()->share('pageTitle', 'Department '. $department->name);
        return view('master_list/departments/view', compact('department','users')); 
    }

    // LOCATIONS
    public function locationList(Request $request)
    { 
        Gate::authorize('superadmin');
        $locations = Location::all();

       view()->share('pageTitle', 'Location List');
        return view('master_list/locations/list', compact('locations')); 
    }
    
    public function saveLocation(Request $request){
        Gate::authorize('superadmin');
        $location = new Location();
        $location->name = $request->input('name');
        $location->description = $request->input('description');
        $location->save();
        
        return redirect()->route('location-list');

    }


    // CLASSIFICATION
    public function classificationList(Request $request)
    { 
        Gate::authorize('superadmin');
        $classifications = Classification::all();

       view()->share('pageTitle', 'Classification List');
        return view('master_list/classifications/list', compact('classifications')); 
    }
    
    public function saveClassification(Request $request){
        Gate::authorize('superadmin');
        $classification = new Classification();
 
        $classification->name = $request->input('name');
        $classification->description = $request->input('description');
        $classification->save();
        
        return redirect()->route('classification-list');

    }



    // CATEGORY
    public function categoryList(Request $request)
    { 
        Gate::authorize('superadmin');
        $categories = Category::all();

       view()->share('pageTitle', 'Category List');
        return view('master_list/categories/list', compact('categories')); 
    }
    
    public function saveCategory(Request $request){
        Gate::authorize('superadmin');
        $category = new Category();
 
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->save();
        
        return redirect()->route('category-list');

    }

    // HIERARCHY
    public function hierarchyList(Request $request)
    { 
        Gate::authorize('superadmin');
        $hierarchies = ApprovalHierarchy::with(['user','approver_user','confirmer_user'])->get();
        $users = User::all();
        $departments = Department::all();

       view()->share('pageTitle', 'Hierarchy List');
        return view('master_list/hierarchy/list', compact('hierarchies','users','departments')); 
    }
    
    // public function saveHierarchy(Request $request,$deptid){
        
    //     $data = [];
    //     foreach($request->hierarchy as $index => $users){
    //         foreach($users as $user){
    //             $index = trim($index, "'\"");
    //             if($user){
    //                 $data[] = [
    //                     'user_id' => $user,
    //                     'type'  => $index == 'approver_user' ? 2 : ($index == 'confirmer_user' ?  3 : 1),
    //                     'deptid'   => $deptid,
    //                 ];
    //             }
    //         }
    //     }

    //     $data = collect($data)
    //         ->unique(fn ($row) => $row['deptid'].'-'.$row['type'].'-'.$row['user_id'])
    //         ->values()
    //         ->toArray();

    //     $newKeys = collect($data)->map(fn ($r) => "{$r['deptid']}-{$r['type']}-{$r['user_id']}");

    //     ApprovalHierarchy::get()->each(function ($row) use ($newKeys) {
    //         $key = "{$row->deptid}-{$row->type}-{$row->user_id}";
            
    //         if (!$newKeys->contains($key)) {
    //             ApprovalHierarchy::where('deptid', $row->deptid)
    //                 ->where('type', $row->type)
    //                 ->where('user_id', $row->user_id)
    //                 ->delete();
    //         }
    //     });
    //     // DD($data);
    //     ApprovalHierarchy::upsert($data, ['deptid','type','user_id'],[]);


                
    //     return redirect()->route('view-department',$deptid);

    // }


    public function saveHierarchy(Request $request,$deptid){
        Gate::authorize('superadmin');
        
        $data = [];
        $department = Department::find($deptid);


        $department->update([
            'preparedby2' => $request->hierarchy['user'][0],
            'confirmed1' => $request->hierarchy['confirmer_user'][0],
            'confirmed2' => $request->hierarchy['confirmer_user'][1],
            'approved1' => $request->hierarchy['approver_user'][0],
            'approved2' => $request->hierarchy['approver_user'][1],
        ]);
                
        return redirect()->route('view-department',$deptid);

    }

    //USERS
    public function userList(Request $request){
        Gate::any(['auditor', 'superadmin']);
        $users = User::all();
        $roles = Role::all();
        $departments = Department::all();

       view()->share('pageTitle', 'User List');
        return view('master_list/users/list', compact('users','roles','departments')); 
    }

    public function saveUser(Request $request){
        Gate::any(['auditor', 'superadmin']);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'deptid' => ['required', 'string'],
            'role_id' => ['required', 'string'],
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'signature' => 'nullable|image|mimes:png|max:1024',
        ]);



        if ($request->hasFile('picture')) {
            $picture = $request->file('picture')->store('users/pictures', 'public');
        }

        if ($request->hasFile('signature')) {
            $signature = $request->file('signature')->store('users/signatures', 'public');
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'deptid' => $request->deptid,
            'role_id' => $request->role_id,
            'photo' => $picture ?? 'users/pictures/avatar.png',
            'signature' => $signature ?? 'users/pictures/sign.png',
        ]);

        

        // event(new Registered($user));

        // Auth::login($user);

       return redirect()->route('user-list');

    }
    public function updateUser(Request $request,$user_id){
        Gate::any(['auditor', 'superadmin']);
        $user = User::find($user_id);

        $request->validate([
            'name' => ['string', 'max:255'],
            'email' => [
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class . ',email,' . $user->id
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'deptid' => ['string'],
            'role_id' => ['string'],
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'signature' => 'nullable|image|mimes:png|max:1024',
        ]);




        if ($request->hasFile('picture')) {
            $picture = $request->file('picture')->store('users/pictures', 'public');
        }else{
            $picture = $user->picture ?? 'users/pictures/avatar.png';
        }

        if ($request->hasFile('signature')) {
            $signature = $request->file('signature')->store('users/signatures', 'public');
        }else{
             $signature =$user->signature ?? 'users/pictures/sign.png';
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'deptid' => $request->deptid,
            'role_id' => $request->role_id,
            'photo' => $picture,
            'signature' =>$signature,
        ]);

        if ($request->hasFile('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

       return redirect()->route('user-list');
    }
    

    // ROLE
    public function roleList(Request $request)
    { 
        Gate::authorize('superadmin');
        $roles = Role::all();

       view()->share('pageTitle', 'Role List');
        return view('master_list/role/list', compact('roles')); 
    }
    
    public function saveRole(Request $request){
        Gate::authorize('superadmin');
        $role = new Role();
 
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->save();
        
        return redirect()->route('role-list');

    }

}


