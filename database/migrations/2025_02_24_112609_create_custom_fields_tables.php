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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('rescue_company_id')->constrained();
            $table->foreignId('parent_field')->nullable()->constrained('custom_fields');

            $table->string('name');
            $table->string('label')->nullable();
            $table->string('model');
            $table->boolean('required')->default(false);
            $table->string('validation')->nullable();
            $table->enum('type', ['int', 'float', 'string', 'text', 'datetime', 'boolean', 'single_choice_enum', 'multiple_choice_enum', 'multi_value']);
            $table->jsonb('parameters')->nullable();
        });

        Schema::create('custom_field_options', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('custom_field_id')->constrained();
            $table->string('name');
            $table->string('label')->nullable();
            $table->integer('order')->default(0);
        });

        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('custom_field_id')->constrained();
            $table->morphs('model');
            $table->string('value_string')->nullable();
            $table->text('value_text')->nullable();
            $table->integer('value_int')->nullable(); // will also store custom options selections
            $table->float('value_float')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->dateTime('value_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_field_values');
        Schema::dropIfExists('custom_field_options');
        Schema::dropIfExists('custom_fields');
    }
};
