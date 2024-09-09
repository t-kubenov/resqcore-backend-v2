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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_office_id')->index();
            $table->foreignId('facility_id')->nullable()->index();
            $table->foreignId('facility_owner_id')->nullable()->index();
            $table->foreignId('reported_by')->index();
            $table->string('report_code');
            $table->string('caller');
            $table->string('line_facility_location')->nullable();
            $table->string('line_facility_owner')->nullable();
            $table->string('emergency_location')->nullable();
            $table->string('emergency_info')->nullable();
            $table->datetime('call_date');
            $table->datetime('work_start_date');
            $table->integer('emergency_type');
            $table->integer('minor_injury_count')->nullable(); //TODO: remove nullable, default to 0
            $table->integer('severe_injury_count')->nullable();
            $table->integer('fatal_injury_count')->nullable();
            $table->integer('evacuated_people_count')->nullable();
            $table->integer('provided_medical_count')->nullable();
            $table->integer('client_equipment_count')->nullable();
            $table->integer('third_parties_count')->nullable();
            $table->integer('branch_worker_count')->nullable();
            $table->float('worker_man_hours')->nullable();

            $table->integer('minor_injury_total')->nullable();
            $table->integer('severe_injury_total')->nullable();
            $table->integer('fatal_injury_total')->nullable();
            $table->integer('evacuated_people_total')->nullable();
            $table->integer('provided_medical_total')->nullable();
            $table->integer('client_equipment_total')->nullable();
            $table->integer('third_parties_total')->nullable();

            $table->text('work_scope');
            $table->text('situation_during_filing')->nullable();
            $table->text('other_info')->nullable();
            $table->boolean('is_emergency_line')->default(false);
            $table->datetime('close_date')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
