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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner')->references('id')->on('companies');
            $table->string('location');
            $table->foreignId('overseeing_branch')->references('id')->on('branch_offices');
            $table->timestamps();
        });
    }

    // Table facility {
    //     id bigint [pk, increment]
    //     owner bigint [ref:> companies.id]
    //     location string
    //     overseeing_branch bigint [ref: > branch_office.id]

    //   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
