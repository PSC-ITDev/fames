<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\AssetCategory;
use App\Models\AssetClassification;
use App\Models\AssetLocation as Location;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Department::create([
            'code' => 'FIN',
            'description' => 'Finance Department',
        ]);
        Department::create([
            'code' => 'HR',
            'description' => 'Human Resources Department',
        ]);
        Department::create([
            'code' => 'IT',
            'description' => 'Information Technology Department',           
        ]);
        
        Department::create([
            'code' => 'OPS',
            'description' => 'Operations Department',           
        ]); 
        
        AssetCategory::create([
            'code' => 'VEH',
            'description' => 'Vehicles',
        ]);
        AssetCategory::create([
            'code' => 'EQP',        
            'description' => 'Equipment',
        ]); 
        AssetCategory::create([
            'code' => 'FUR',        
            'description' => 'Furniture',
        ]);
        AssetClassification::create([       
            'code' => 'CAP',        
            'description' => 'Capital Asset',
        ]);
        AssetClassification::create([
            'code' => 'EXP',        
            'description' => 'Expense Asset',
        ]);
        Location::create([  
            'code' => 'WH1',        
            'description' => 'Warehouse 1',
        ]);
        Location::create([
            'code' => 'WH2',            
            'description' => 'Warehouse 2', 
        ]);
        Location::create([
            'code' => 'OFF',
            'description' => 'Office',
        ]);

    }
}
