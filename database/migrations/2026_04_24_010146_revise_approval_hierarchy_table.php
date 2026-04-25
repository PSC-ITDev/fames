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
        Schema::table('approval_hierarchies', function (Blueprint $table) {
            $table->dropColumn('approver');
            $table->dropColumn('confirmer');
            $table->Integer('type'); //1 creator 2 approver 3 confirmer
            $table->Integer('deptid');
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
