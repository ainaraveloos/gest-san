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
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('designation'); // Désignation du médicament
            $table->string('forme'); // Forme du médicament (comprimé, sirop, etc.)
            $table->text('posologie'); // Posologie du médicament
            $table->integer('quantite'); // Quantité prescrite
            $table->foreignId('ordonnance_id')->constrained('ordonnances')->onDelete('cascade'); // Lien vers l'ordonnance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicaments');
    }
};
