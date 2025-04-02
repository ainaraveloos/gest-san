<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    /** @use HasFactory<\Database\Factories\MedecinFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'specialite',
        'type',
        'user_id'
    ];

    /**
     * Get the consultations for the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
public function lettresReferences()
{
    return $this->hasMany(Lettre_reference::class, 'refere_med_id');
}
 // Relation avec les patients via les consultations
    public function patients()
    {
        return $this->hasManyThrough(
            Patient::class,      // Le modèle final
            Consultation::class, // Le modèle intermédiaire
            'medecin_id',        // Clé étrangère sur la table consultations vers medecin
            'id',                // Clé primaire de Patient
            'id',                // Clé primaire de Medecin
            'patient_id'         // Clé étrangère sur la table consultations vers patient
        );
    }

}
