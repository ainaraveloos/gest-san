<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'numero',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'poste',
        'adresse',
        'telephone',
        'email',
        'societe_id',
        'parent_id',
        'lien_parente',
        'date_entree_entreprise',
        'statut_emploi',
        'date_fin_contrat',



        'type', // 'SALARIE' ou 'FAMILLE'
        'poids',
        'taille',
        'freq_card',
        'temperature',
        'imc', // Indice de masse corporelle
    ];

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function salarie()
    {
        return $this->belongsTo(Patient::class, 'parent_id');
    }


    // Scope pour filtrer les types
    public function scopeSalaries($query)
    {
        return $query->where('type', 'SALARIE');
    }

    public function badge()
    {
        return $this->hasOne(Badge::class);
    }

    // Scope de filtrage dans le modèle Patient
    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('numero', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['societes'])) {
            // On suppose que la relation 'societe' existe et que la colonne "nom" correspond au nom de la société.
            $query->whereHas('societe', function($q) use ($filters) {
                $q->where('nom', 'like', "%{$filters['societe']}%");
            });
        }
        return $query;}
}
