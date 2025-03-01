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
        Schema::create('blood_pressures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con usuarios
            $table->integer('systolic'); // Presión sistólica
            $table->integer('diastolic'); // Presión diastólica
            $table->integer('pulse')->nullable(); // Pulso (opcional)
            $table->decimal('temperature', 4, 1)->nullable(); // Temperatura en °C (opcional)
            $table->dateTime('measured_at'); // Fecha y hora de la medición
            $table->text('notes')->nullable(); // Notas opcionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_pressures');
    }
};
