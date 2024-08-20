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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_office_id')->index();
            $table->foreignId('facility_id')->nullable()->index();
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
            $table->integer('minor_injury_count')->nullable();
            $table->integer('severe_injury_count')->nullable();
            $table->integer('fatal_injury_count')->nullable();
            $table->integer('evacuated_people_count')->nullable();
            $table->integer('provided_medical_count')->nullable();
            $table->integer('client_equipment_count')->nullable();
            $table->integer('third_parties_count')->nullable();
            $table->integer('branch_worker_count')->nullable();
            $table->float('worker_man_hours')->nullable();
            $table->text('work_scope');
            $table->text('situation_during_filing')->nullable();
            $table->text('other_info')->nullable();
            $table->boolean('is_emergency_line')->default(false);
            $table->datetime('close_date')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });
    }

        /*

        Table operations {
    id bigint [pk, increment]
    branch_id bigint [ref: > branch.id]
    object_id bigint [ref: > object.id] // if it's on the emergency line
    reported_by bigint [ref: > users.id]
    report_code string // code of the report , i.e. 10-4
    caller string // ex who_call
    emergency_location string // ex location_accident; if the object is not from the emergency line,..
    emergency_info string // ex object_data Данные по аварии
    call_date datetime
    arrival_date datetime
    emergency_type int // enum basically
    minor_injury_count int
    severe_injury_count int
    fatal_injury_count int
    evacuated_people_count int // ex people_evacuated
    provided_medical_count int
    client_equipment_count int // ex customer_technology
    third_parties_count int // ex party_authority other organizations involved
    branch_worker_count int // ex branch_workers varchar
    worker_man_hours float // used to be merged with branch worker count
    work_scope text // ex scope_work
    situation_during_filing text // ex situation_filing
    other_info text // ex other_data
    is_emergency_line boolean [default: false] // ex line_emergency
    close_date datetime // ex finish_work_date
    is_closed boolean // ex status
    created_at timestamp
    updated_at timestamp
    // object_owner varchar // object_id deprecates this
}

    */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
