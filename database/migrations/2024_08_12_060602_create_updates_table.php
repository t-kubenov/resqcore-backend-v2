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
        Schema::create('updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->references('id')->on('reports');
            $table->integer('minor_injury_count')->default(0);
            $table->integer('severe_injury_count')->default(0);
            $table->integer('fatal_injury_count')->default(0);
            $table->integer('evacuated_people_count')->default(0);
            $table->integer('provided_medical_count')->default(0);
            $table->text('work_scope');
            $table->text('situation_during_filing')->nullable();
            $table->integer('client_equipment_count')->default(0);
            $table->integer('third_parties_count')->default(0);
            $table->integer('branch_worker_count')->nullable();
            $table->float('worker_man_hours')->nullable();
            $table->datetime('close_date')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('updates');
    }
};
