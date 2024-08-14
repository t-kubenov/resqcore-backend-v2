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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id');
            $table->foreignId('operation_update_id')->nullable();
            $table->integer('type');
            $table->integer('count');
            $table->timestamps();
        });
    }

    /*

  id bigint [pk, increment]
  branch_id bigint [ref: > branch_office.id]
  operation_id bigint [ref:> operations.id]
  operation_update_id bigint [ref:> operation_updates.id]
  type int //enum or maybe another table
} // should write the table as a preparation for the company information?


    */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
