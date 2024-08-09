<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /*
    "id",
    "branch_id",
    "accident_id",
    "reporting_id",
    "who_call",
    "location_accident",
    "call_date",
    "emergency_name",
    "status",
    "object_owner",
    "branch_workers",
    "arrival_date",
    "finish_work_date",
    "object_data",
    "minor_injury",
    "severe_injury",
    "fatal_injury",
    "people_evacuated",
    "provided_medical",
    "scope_work",
    "situation_filing",
    "customer_technology",
    "party_authority",
    "other_data",
    "line_emergency",
    */


    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('accident_id');
            $table->unsignedBigInteger('reporting_id');
            $table->string('who_call');
            $table->string('location_accident');
            $table->datetime('call_date');
            $table->datetime('arrival_date');
            $table->integer('emergency_type');
            $table->string('object_owner');
            $table->string('branch_workers');
            $table->string('object_data');
            $table->integer('minor_injury');
            $table->integer('severe_injury');
            $table->integer('fatal_injury');
            $table->integer('people_evacuated');
            $table->integer('provided_medical');
            $table->text('scope_work');
            $table->text('situation_filing');
            $table->integer('customer_technology');
            $table->integer('party_authority');
            $table->text('other_data');
            $table->boolean('line_emergency')->nullable()->default(false);
            $table->datetime('finish_work_date');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
