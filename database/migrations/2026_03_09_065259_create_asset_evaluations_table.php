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
        Schema::create('asset_evaluations', function (Blueprint $table) {
            $table->id();             
            $table->foreignId('department_id');                 
            
            $table->string('year');     
            $table->string('quarter');
                        
            $table->integer('approval_status')->default(0); //0 - Draft, 1 - Pending Confirmation, 2 - Confirmed 3 - IA Acknowledge 

            $table->integer('assets_on_inventory')->default(0);
            $table->integer('assets_written_off')->default(0);


            $table->integer('confirmed_by')->nullable();
            $table->datetime('confirmed_date')->nullable();
            
            $table->integer('created_by')->nullable();            
            $table->integer('updated_by')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_evaluations');
    }
};
