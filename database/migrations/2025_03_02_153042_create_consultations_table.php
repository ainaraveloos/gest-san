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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->text('motif');
            $table->text('diagnostic');
            $table->enum('type',['maladie','visite_aptitude']);
            $table->dateTime('date_consultation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
