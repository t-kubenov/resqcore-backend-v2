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
        Schema::table('updates', function (Blueprint $table) {
            $table->foreignId('reported_by')->references('id')->on('users');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->datetime('last_update_created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('updates', function (Blueprint $table) {
            $table->dropForeign(['reported_by']);
            $table->dropColumn('reported_by');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('last_update_created_at');
        });
    }
};
