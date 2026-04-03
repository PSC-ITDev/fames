<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id(); 
            $table->string('asset_no')->unique(); // Unique identifier (e.g., FA-1001)
            $table->string('serial_number')->nullable();           
            $table->integer('department');  // Department 
            $table->date('capitalization_date');
            $table->integer('qty');
            $table->string('bun'); // Basic Unit of Measure       
            $table->string('asset_description');            
            $table->decimal('acquired_value', 15, 2);
            $table->decimal('end_book_value', 15, 2);
            $table->string('cost_center');
            $table->string('location');
            $table->string('other_identifier')->nullable();
            $table->foreignId('classification_id');             
            $table->decimal('salvage_value', 15, 2)->default(1); // Value at end of life
            $table->integer('useful_life_years')->default(0); // For depreciation calculations
            
            // Relationships (Convention: singular_table_id)
            $table->foreignId('category_id'); 
            $table->foreignId('location_id');
            
            $table->text('notes')->nullable();
            $table->softDeletes(); // Adds deleted_at for archiving assets
            $table->timestamps();


            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};

// Asset_No.
// SNo.
// Capitalization_Date
// Quantity
// BUn
// Asset_Description
// ODep.Start
// Cum.acq.value
// Accum.dep.
// End_book_val
// Life
// Class
// Asset_Class_Description
// Cost_Ctr
// Cost_Center_Desc
// Dept
// Location
// GL_Account
// GL_Account_Description
// Start_book.val
// Trans.acq.val
// PlndDep
// Pld.unpl.dep.
// Sep.pln.ord.dep
// Sep.cum.ord.dep
// Sep.cum.acq.val
// Trns.ord.dep.

