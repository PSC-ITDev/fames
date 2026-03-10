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
        Schema::create('asset_evaluation_details', function (Blueprint $table) {
            $table->id();
            $table->integer()->foreig;
            $table->foreignId('asset_id');
            
            $table->string('corrective_actiion_taken')->nullable();      
            $table->integer('asset_status'); //1 - none , 2 - checkedOut, 3 -inStock, 4 - inStorage, 5 - inUse, 6- outForRepair
            // 1 - Idle = Good Condition but not in use, 2 - Active = Currently been utilized, 3 - UnderMainteance = Repair in progress ,WriteOff = Asset Disposal

            $table->boolean('iswrite_off')->default('false');
            $table->string('reason_for_writeoff')->nullable();
            $table->integer('writeoff_qty')->default(0);

            $table->datetime2('turnover_date')->nullable();
            $table->datetime2('adwf_date')->nullable();
            $table->string('adwf_docno')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_evaluation_details');
    }
};
