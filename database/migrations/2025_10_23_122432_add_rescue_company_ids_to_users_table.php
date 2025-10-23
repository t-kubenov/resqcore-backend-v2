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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('rescue_company_id')->nullable()->constrained('rescue_companies');
        });

        DB::statement('
            UPDATE users
            SET rescue_company_id = branch_offices.rescue_company_id
            FROM branch_offices
            WHERE users.branch_id = branch_offices.id
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rescue_company_id']);
            $table->dropColumn('rescue_company_id');
        });
    }
};
