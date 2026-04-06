<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Division;
use App\Models\AssetCategory as Category;
use App\Models\AssetClassification as Classification;
use App\Models\AssetLocation as Location;
use App\Models\FixedAsset as Asset;
use App\Models\CostCenter;
use App\Models\GLAccount;
use App\Models\User;
use App\Models\Role;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {  
    
    Role::upsert([
        ['name' => 'SuperAdmin', 'description' => 'Administrator with full access'],
        ['name' => 'Admin', 'description' => 'Administrator with limited access'],
        ['name' => 'User', 'description' => 'Regular user with limited access'],
    ], ['name'], ['description']);
    
    User::upsert([
        [
            'name'     => 'Application Admin',
            'email'    => 'karel.dagus@philsinter.com.ph',
            'role_id' => Role::where('name', 'SuperAdmin')->first()->id,    
            'password' => bcrypt('K4relmark'),
        ],
        [
            'name'     => 'Asset Admin',
            'email'    => 'dev@philsinter.com.ph',
            'role_id' => Role::where('name', 'Admin')->first()->id,    
            'password' => bcrypt('P@ssw0rd'),
        ],
         [
            'name'     => 'Asset User',
            'email'    => 'ken.remolisan@philsinter.com.ph',
            'role_id' => Role::where('name', 'User')->first()->id,    
            'password' => bcrypt('P@ssw0rd'),
        ],
    ], ['email'], ['name']);  

    $this->command->info('User created.');

    Division::upsert([
        ['code' => 'PROD', 'name' => 'Production Division', 'description' => 'Production Division'],
        ['code' => 'MAIN', 'name' => 'Maintenance Division', 'description' => 'Maintenance Division'],
        ['code' => 'GEMA', 'name' => 'General Management Division', 'description' => 'General Management Division'],
        ['code' => 'FPLN', 'name' => 'Financial Planning Division', 'description' => 'Financial Planning Division'],
        ['code' => 'IA', 'name' => 'Internal Audit Division', 'description' => 'Internal Audit Division'],
        ['code' => 'ORM-SP', 'name' => 'Office of the Resident Manager Division', 'description' => 'Office of the Resident Manager Division'],
        
     ], ['code']);


    Department::upsert([
                ['code' => 'SNT','division_id'=> '1','name'=> 'Sinter', 'description' => 'Sinter Department'],
                ['code' => 'BLP','division_id'=> '1','name'=> 'Burnt Lime Plant', 'description' => 'Burnt Lime Plant'],
                ['code' => 'POWER', 'division_id'=> '1','name' => 'Power Plant', 'description' => 'Power Plant'],
                ['code' => 'SHR', 'division_id'=> '1','name' => 'Sinter Heat Recovery', 'description' => 'Sinter Heat Recovery Department'],
                ['code' => 'MTH', 'division_id'=> '1','name' => 'Material Handling', 'description' => 'Material Handling Department'],                
                ['code' => 'MEC', 'division_id'=> '2','name' => 'Mechanical', 'description' => 'Mechanical Department'],
                ['code' => 'ELE', 'division_id'=> '2','name' => 'Electrical', 'description' => 'Electrical Department'],
                ['code' => 'PLN', 'division_id'=> '2','name' => 'Planning', 'description' => 'Planning Department'],
                ['code' => 'SAFETY', 'division_id'=> '2','name' => 'Safety', 'description' => 'Safety Department'],
                ['code' => 'FPLN', 'division_id'=> '4','name' => 'Financial Planning', 'description' => 'Financial Planning Department'],
                ['code' => 'SUMA', 'division_id'=> '3','name' => 'Supply Material', 'description' => 'Supply Material Department'],
                ['code' => 'HRGA', 'division_id'=> '3','name' => 'Sinter Plant Human Resource', 'description' => 'Sinter Plant Human Resource Department'],
                ['code' => 'IA', 'division_id'=> '5','name' => 'Internal Audit', 'description' => 'Internal Audit Department'],
                ['code' => 'LAB', 'division_id'=> '6','name' => 'Laboratory and Environment', 'description' => 'Laboratory and Environment Department'],
                ['code' => 'ITG', 'division_id'=> '6','name' => 'Information Technology', 'description' => 'Information Technology Department']
            ], ['code']);


    Category::upsert([
                ['name' => 'VEH', 'description' => 'Vehicles'],
                ['name' => 'EQP', 'description' => 'Equipment'],
                ['name' => 'FUR', 'description' => 'Furniture'],
            ], ['name'], ['description']);
 


         Classification::upsert([       
            ['name' => '10700040', 'description' => 'FOH-Tools and equipment'],
            ['name' => '10700050', 'description' => 'FOH-Office Equipment'],
            ['name' => '10700060', 'description' => 'FOH-Furniture and Fixtures'],
            ['name' => '10700070', 'description' => 'FOH-IT Equipment'],
            ['name' => '10700080', 'description' => 'FOH-Vehicles'],
            ['name' => '10700090', 'description' => 'FOH-Other Assets'],
            ['name' => '10700100', 'description' => 'FOH-Assets under construction'],
            ['name' => '30700040', 'description' => 'GEN-Tools and equipment'],
            ['name' => 'CAP', 'description' => 'Capital Asset'],
            ['name' => 'EXP', 'description' => 'Expense Asset'],
        ], ['name'], ['description']);



       Location::upsert([

            ['name' => 'WH1', 'description' => 'Warehouse 1'],
            ['name' => 'WH2', 'description' => 'Warehouse 2'],
            ['name' => 'OFF', 'description' => 'Office'],
        ], ['name'], ['description']);



        GLAccount::upsert([
            ['code' => '164500', 'description' => 'Tools and Equipment'],
            ['code' => '164000', 'description' => 'Office Equipment'],
            ['code' => '163500', 'description' => 'Furniture and Fixtures'],
            ['code' => '163000', 'description' => 'IT Equipment'],
            ['code' => '162500', 'description' => 'Vehicles'],
            ['code' => '162000', 'description' => 'Other Assets'],
            ['code' => '161500', 'description' => 'Assets under construction'],
        ], ['code'], ['description']);



        $costCenters = [

            ['code' => 21500, 'description' => 'B/L PLANT, COMMON'],
            ['code' => 21601, 'description' => 'KILN-1'],
            ['code' => 21602, 'description' => 'KILN-2'],
            ['code' => 34100, 'description' => 'ELECTRICAL'],
            ['code' => 17000, 'description' => 'GENERAL PLANT - SP'],
            ['code' => 15100, 'description' => 'FOOD SERVICES'],
            ['code' => 11300, 'description' => 'PERSONNEL - SP'],
            ['code' => 11000, 'description' => 'JAPANESE STAFFHOUSE'],
            ['code' => 52000, 'description' => 'ITG'],
            ['code' => 20540, 'description' => 'LABORATORY'],
            ['code' => 33000, 'description' => 'MECHANICAL'],
            ['code' => 23500, 'description' => 'MTH, COMMON'],
            ['code' => 22200, 'description' => 'MARINE OPERATION'],
            ['code' => 22100, 'description' => 'UNLOADING'],
            ['code' => 22101, 'description' => 'UNLOADER 1'],
            ['code' => 21099, 'description' => 'Y&B, COMMON'],
            ['code' => 22000, 'description' => 'MAIN BERTH'],
            ['code' => 23000, 'description' => 'BULLDOZERS'],
            ['code' => 22102, 'description' => 'UNLOADER 2'],
            ['code' => 35000, 'description' => 'PLANNING/ENGG'],
            ['code' => 33200, 'description' => 'CIVIL'],
            ['code' => 23600, 'description' => 'POWER SUPPLY'],
            ['code' => 43000, 'description' => 'SAFETY'],
            ['code' => 25000, 'description' => 'SHR POWER PLANT'],
            ['code' => 20500, 'description' => 'SINTERING, COMMON'],
            ['code' => 20800, 'description' => 'DUST TREATMENT'],
            ['code' => 24000, 'description' => 'H/L PLANT, COMMON'],
            ['code' => 20900, 'description' => 'CONVEYING, SINTERING'],
            ['code' => 20600, 'description' => 'COOLING'],
            ['code' => 13300, 'description' => 'SUPPLY MATERIAL'],

        ];



        foreach ($costCenters as $center) {

            CostCenter::updateOrCreate(
                ['code' => $center['code']],
                ['description' => $center['description']]
            );

        }


        // 2. CSV Import Logic
        $file = fopen(database_path('data/assets.csv'), 'r');
        fgetcsv($file); // Skip header
        $line = 0;
        while (($row = fgetcsv($file)) !== FALSE) {
            $line++;
        try {
            if (empty($row[0])) {
                continue;
            }
            // Match 'description' from CSV to the 'description' in your database
            $category = Category::firstOrCreate(
                ['description' => $row[12]], 
                ['name' => '' . substr($row[12], 0, 3)] 
            );

            $location = Location::firstOrCreate(['name' => $row[16]]);
            $classification = Classification::firstOrCreate(['name' => $row[11]], ['description' => 'Classification for ' . $row[11]]);     
            // Find the actual ID for CostCenter and GLAccount
            $costCenter = CostCenter::where('code', $row[13])->first();
            $glAccount = GLAccount::where('code', $row[17])->first();

            // Map Life (005/000)
            $usefulLifeYears = isset($row[10]) ? (int) explode('/', $row[10])[0] : 0;
            
            // 1. Clean the description (removes the problematic characters)
            $description = mb_convert_encoding($row[5], 'UTF-8', 'UTF-8');
            $description = str_replace('', '-', $description); // Optional: Replace the 'broken' char with a dash
            
            $department = Department::firstOrCreate(
                            ['code' => $row[15]], // Search criteria
                            ['description' => 'New Department: ' . $row[15]] // Attributes to set if creating
                        );

            Asset::create([
                'asset_number'                  => $row[0],
                'capitalization_date'           => $row[2],
                'qty'                           => (int) $row[3],
                'bun'                           => $row[4],
                'asset_description'             => $description,
                'useful_life_years'             => $usefulLifeYears,
                'department_id'                 => $department->id, 
                'other_identifier'              => '', // Placeholder if you have another identifier
                'ordinary_depreciation_start_date' => $row[6],   
                'cumulative_acquisition_value'  => (float) $row[8], 
                'accumulated_depreciation'      =>  (float) $row[9] , 
                'salvage_value'                 =>  1.00,
                'transfer_acquisition_value'    => 0.00,
                'start_book_val'              => (float) $row[7], // Assuming acquisition value is the start book value
                'end_book_val'                => (float) $row[9], // Initial end book value same as acquisition, will be updated after depreciation calculations
                // Use ->id to ensure foreign key integrity
                'cost_center_id'                => $costCenter?->id,
                'gl_account_id'                 => $glAccount?->id,
                'category_id'                   => $category->id,
                'location_id'                   => $location->id,
                'classification_id'             => $classification->id, // Reusing classification logic
            ]);
        } catch (\Exception $e) {
        dump("Error on CSV line {$line}: " . $e->getMessage());
        dump($row); // This will show you exactly what is in that row
        break; 
        }
        }
        fclose($file);
        $this->command->info('Import completed successfully.');
    }
}
