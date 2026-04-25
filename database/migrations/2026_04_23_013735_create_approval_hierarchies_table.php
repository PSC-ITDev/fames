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
        Schema::create('approval_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->Integer('user_id');
            $table->Integer('approver');//user_id
            $table->Integer('confirmer');//user_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_hierarchies');
    }
};
