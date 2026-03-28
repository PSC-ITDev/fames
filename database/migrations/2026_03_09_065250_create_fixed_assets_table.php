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
            $table->integer('item')->nullable();
            $table->string('asset_no')->unique(); // Unique identifier (e.g., FA-1001)
            $table->string('serial_number')->nullable();           
            $table->integer('department')->nullable();  // Department 
            $table->date('capitalization_date')->nullable();
            $table->integer('qty')->nullable();
            $table->string('bum')->nullable(); // Basic Unit of Measure       
            $table->string('asset_description')->nullable();            
            $table->decimal('acquired_value', 15, 2)->nullable();
            $table->decimal('end_book_value', 15, 2)->nullable();
            $table->string('cost_center')->nullable();
            $table->string('location')->nullable();
            $table->string('other_identifier')->nullable();

            $table->foreignId('classification_id')->nullable();
            
            $table->decimal('salvage_value', 15, 2)->default(1)->nullable(); // Value at end of life
            $table->integer('useful_life_years')->default(0)->nullable(); // For depreciation calculations
            
            // Relationships (Convention: singular_table_id)
            $table->foreignId('category_id')->nullable(); 
            $table->foreignId('location_id')->nullable();
            
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
