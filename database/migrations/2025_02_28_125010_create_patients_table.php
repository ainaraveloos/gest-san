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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societe_id')->constrained('societes')->onDelete('cascade');
            $table->enum('type', ['SALARIE', 'FAMILLE']);//type de pations
            $table->foreignId('parent_id')->nullable()->constrained('patients')->onDelete('cascade');//Pour le type Famille,reference vers le patient salarié
            $table->string('lien_parente')->nullable();
            $table->string('numero');

            //information personnelles (type salarié et famille)
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            //information après consultation
            $table->integer('poids')->nullable();
            $table->integer('taille')->nullable();
            $table->integer('freq_card')->nullable();
            $table->integer('temperature')->nullable();
            $table->text('observation')->nullable();
            $table->float('imc')->nullable(); // IMC (Indice de Masse Corporelle)
            //information profesionnelles(type salarié)
            $table->string('poste')->nullable();
            $table->date('date_entree_entreprise')->nullable();
            $table->string('identifiant_salarie')->nullable();
            $table->enum('statut_emploi', ['Permanent','Temporaire', 'CDD', 'CDI'])->nullable();
            $table->date('date_fin_contrat')->nullable();//pour status_emploi temporaire ou CDD
            $table->string('numero_travailleur')->nullable();
            //Status de l'adhesion
            $table->date('date_adhesion')->nullable();
            $table->enum('type_adhesion',['famille','association','formelle'])->nullable();
            $table->date('date_debauche')->nullable();
            $table->string('motif_debauche')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
