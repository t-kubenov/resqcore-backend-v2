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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            // FK to branch_offices (each vehicle belongs to a branch)
            $table->foreignId('branch_office_id')
                ->constrained()
                ->cascadeOnDelete();
            // Тип (e.g., truck, car, ambulance)
            $table->foreignId('vehicle_type_id')
                ->constrained()
                ->cascadeOnDelete();
            // Вид пользования
            $table->enum('usage_type', [
                'rental',
                'customer_rental',
                'owned',
                'customer_owned',
                'transferred_ownership',
            ]);
            // Действия
            $table->enum('action', [
                'for_sale',
                'transfer',
                'acquired',
                'reserved',
                'none',
            ])->default('none');
            // Тех. состояние
            $table->enum('technical_condition', [
                'operational',
                'faulty'
            ])->nullable();

            $table->string('brand'); // Марка автомашины
            $table->string('plate_number')->unique(); // Гос. номер
            $table->string('image_path')->nullable();
            $table->year('year')->nullable();    // Год выпуска

            $table->string('location')->nullable(); // Место дислокации
            $table->foreignId('vehicle_owner_id')->constrained('vehicle_companies')->cascadeOnDelete();    // Владелец
            $table->foreignId('vehicle_user_id')->constrained('vehicle_companies')->cascadeOnDelete(); // Организация-эксплуатант

            $table->string('rental_contract')->nullable(); // Договор аренды (номер/ссылка)
            $table->decimal('rental_price', 10, 2)->nullable(); // Стоимость в месяц

            $table->text('notes')->nullable(); // Уточнения

            // Dates
            $table->date('insurance_expiration_date')->nullable();
            $table->date('inspection_expiration_date')->nullable();
            $table->date('inspection_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
