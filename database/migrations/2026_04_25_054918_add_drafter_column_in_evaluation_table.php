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
        Schema::table('asset_evaluations', function (Blueprint $table) {
            
            $table->Integer('draft_by2')->nullable();
            $table->datetime('draft_date2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_evaluations', function (Blueprint $table) {
            //
        });
    }
};
