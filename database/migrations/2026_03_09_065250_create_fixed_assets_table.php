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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();            
            // Identifiers
            $table->string('asset_number')->unique(); // Primary Asset ID (e.g., FA-1001)
            $table->string('serial_number')->nullable();
            $table->string('other_identifier')->nullable();
            
            // Description & Quantity
            $table->string('asset_description');
            $table->integer('qty')->default(1);
            $table->string('uom')->nullable(); // Standardized from bum/bun
            
            // Categorization & Relationships
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('classification_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('location_id')->nullable()->constrained();
            $table->foreignId('cost_center_id')->nullable();
            $table->foreignId('gl_account_id')->nullable();

            // Dates
            $table->date('capitalization_date')->nullable();
            $table->date('ordinary_depreciation_start_date');
            $table->integer('useful_life_years')->default(0);

            // Financial Values
            $table->decimal('acquisition_value', 15, 2)->default(0); 
            $table->decimal('accumulated_depreciation', 15, 2)->default(0); 
            $table->decimal('salvage_value', 15, 2)->default(1); 
            
            // Period Tracking
            $table->decimal('start_book_val', 15, 2)->default(0);
            $table->decimal('end_book_val', 15, 2)->default(0);

            // Virtual Column (Optional - requires MySQL 5.7+ or MariaDB 10.2+)
            // $table->decimal('net_book_value', 15, 2)
            //       ->virtualAs('acquisition_value - accumulated_depreciation');

            $table->text('notes')->nullable();
            $table->softDeletes(); 
            $table->timestamps();
        });
        DB::statement("
        ALTER TABLE fixed_assets 
        ADD net_book_value AS (cumulative_acquisition_value - ABS(accumulated_depreciation)) PERSISTED
        ");
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

