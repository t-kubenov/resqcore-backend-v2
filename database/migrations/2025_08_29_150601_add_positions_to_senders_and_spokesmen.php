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
        Schema::table('survey_senders', function (Blueprint $table) {
            $table->string('position')->nullable();
        });

        Schema::table('survey_facility_spokesman', function (Blueprint $table) {
            $table->string('position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_senders', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('survey_facility_spokesman', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
