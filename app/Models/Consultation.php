<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'type',
        'date_consultation',
        'motif',
        'diagnostic',
        
    ];

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function ordonnance()
    {
        return $this->hasOne(Ordonnance::class);
    }
    public function demande_examen()
    {
        return $this->hasOne(Demande_examen::class);
    }
    public function lettre_reference()
    {
        return $this->hasOne(Lettre_reference::class);
    }

}
