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
        Schema::create('operation_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->references('id')->on('operations');
            $table->integer('minor_injury_count')->nullable();
            $table->integer('severe_injury_count')->nullable();
            $table->integer('fatal_injury_count')->nullable();
            $table->integer('evacuated_people_count')->nullable();
            $table->integer('provided_medical_count')->nullable();
            $table->text('work_scope');
            $table->text('situation_during_filing')->nullable();
            $table->integer('client_equipment_count')->nullable();
            $table->integer('third_parties_count')->nullable();
            $table->integer('branch_worker_count')->nullable();
            $table->float('worker_man_hours')->nullable();
            $table->datetime('close_date')->nullable();
            $table->timestamps();
        });
    }


    /*

        Table operation_updates {
    id bigint [pk, increment]
    operation_id bigint [ref: > operations.id]
    minor_injury_count int
    severe_injury_count int
    fatal_injury_count int
    evacuated_people_count int // ex people_evacuated
    provided_medical_count int
    work_scope text // ex scope_work
    situation_during_filing text // ex situation_filing
    client_equipment_count int // ex customer_technology
    third_parties_count int // ex party_authority other organizations involved
    branch_worker_count int // ex branch_workers varchar
    worker_man_hours float // used to be merged with branch worker count
    close_date datetime // ex finish_work_date
}

     */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_updates');
    }
};
