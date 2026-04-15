<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id(); 
            $table->string('asset_number')->unique(); // Unique identifier 
            // $table->string('serial_number')
            //         ->virtualAs("CONCAT(asset_number, '-', department)")
            //         ->nullable();            
            $table->string('asset_description');  
            $table->integer('qty');
            $table->string('bun'); // Basic Unit of Measure     
           
            $table->string('other_identifier')->nullable();                      
            $table->date('capitalization_date');
            $table->date('ordinary_depreciation_start_date'); //ODep.Start date the useful life begins to count down
            
            $table->integer('useful_life_years')->default(0); // For depreciation calculations
            //$table->integer('department_id');  // Department 
                        
            // Financial Values
           
            // The Total Gross Cost (Purchase + Improvements)
            $table->decimal('cumulative_acquisition_value', 15, 2)->default(0); 
            // The Total Depreciation written off to date
            $table->decimal('accumulated_depreciation', 15, 2)->default(0); 
            // The "Floor" value (Asset cannot drop below this)
            $table->decimal('salvage_value', 15, 2)->default(1);             
            // Only if you need to track period-specific movements  
            $table->decimal('transfer_acquisition_value', 15, 2)->default(0);       
            
            
            $table->decimal('start_book_val', 15, 2)->default(0); // Book Value at the start of the period
            $table->decimal('end_book_val', 15, 2)->default(0); // Book Value at the end of the period
            // $table->decimal('net_book_value', 15, 2)
            //     ->virtualAs('cumulative_acquisition_value - accumulated_depreciation')->default(0);
           
            // Relationships (Convention: singular_table_id)
            $table->integer('cost_center_id')->nullable();
            $table->integer('gl_account_id')->nullable();
            $table->foreignId('category_id'); 
            $table->foreignId('location_id'); 
            $table->foreignId('classification_id');  
            
            $table->text('notes')->nullable();
            $table->softDeletes(); // Adds deleted_at for archiving assets
            $table->timestamps();

            

                        
        });
        DB::statement("
        ALTER TABLE fixed_assets 
        ADD net_book_value AS (cumulative_acquisition_value - ABS(accumulated_depreciation)) PERSISTED
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
}; 

