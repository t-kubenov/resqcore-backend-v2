<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    static array $alter_tables = [
        'branch_offices',
        'emergency_types',
        'transport_types',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::$alter_tables as $alter_table) {
            Schema::table($alter_table, function (Blueprint $table) {
                $table->foreignId('rescue_company_id')->default(1)->constrained();
                $table->foreignId('rescue_company_id')->default(null)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::$alter_tables as $alter_table) {
            Schema::table($alter_table, function (Blueprint $table) {
                $table->dropForeign(['rescue_company_id']);
                $table->dropColumn('rescue_company_id');
            });
        }
    }
};
