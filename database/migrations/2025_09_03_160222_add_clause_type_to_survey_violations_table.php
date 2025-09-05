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
        Schema::table('survey_violations', function (Blueprint $table) {
            $table->foreignId('clause_type_id')->constrained('survey_violation_clause_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_violations', function (Blueprint $table) {
            $table->dropForeign(['clause_type_id']);
            $table->dropColumn('clause_type_id');
        });
    }
};
