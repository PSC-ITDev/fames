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

            // $table->dropColumn('approved_by');
            // $table->dropColumn('approved_date');
            // $table->dropColumn('confirmed_by');
            // $table->dropColumn('confirmed_date');

            $table->Integer('approved_by1')->nullable();
            $table->datetime('approved_date1')->nullable();
            $table->Integer('approved_by2')->nullable();
            $table->datetime('approved_date2')->nullable();
            $table->Integer('confirmed_by1')->nullable();
            $table->datetime('confirmed_date1')->nullable();
            $table->Integer('confirmed_by2')->nullable();
            $table->datetime('confirmed_date2')->nullable();
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
