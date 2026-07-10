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
         Schema::table('fixed_assets', function (Blueprint $table) {
            
  
            
            // Categorization & Relationships
            $table->unsignedBigInteger('classification_id')->nullable()->change();
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->unsignedBigInteger('location_id')->nullable()->change();

            // Dates
            $table->date('ordinary_depreciation_start_date')->nullable()->change();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
