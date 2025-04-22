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
        Schema::create('legal_entities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('branch_office_id')->constrained()->onDelete('no action');
            $table->string('name');
        });

        Schema::create('legal_entity_units', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('legal_entity_id')->constrained()->onDelete('cascade');
            $table->string('name');
        });

        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('branch_office_id')->constrained()->onDelete('no action');
            $table->foreignId('created_by')->constrained('users')->onDelete('no action');
            $table->foreignId('legal_entity_id')->constrained()->onDelete('no action');
            $table->foreignId('legal_entity_unit_id')->constrained()->onDelete('no action');
            $table->string('survey_code')->unique();
            $table->dateTime('survey_date');
            $table->string('hazardous_facility_name');
        });

        Schema::create('survey_senders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->string('name');
        });

        Schema::create('survey_facility_spokesman', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->string('name');
        });

        Schema::create('survey_violations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('clause');
            $table->date('elimination_prescription_date');
            $table->boolean('elimination_completion')->default(false);
            $table->date('elimination_completion_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_violations');
        Schema::dropIfExists('survey_facility_spokesman');
        Schema::dropIfExists('survey_senders');
        Schema::dropIfExists('surveys');
        Schema::dropIfExists('legal_entity_units');
        Schema::dropIfExists('legal_entities');
    }
};
