<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lettre_reference extends Model
{
    protected $fillable = [
        'consultation_id',
        'refere_med_id',
        'motif_ref'
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
    public function refereMed()
    {
        return $this->belongsTo(Medecin::class, 'refere_med_id');
    }
    public function medecin_ref()
{
    return $this->belongsTo(Medecin::class, 'refere_med_id'); // Assurez-vous que 'refere_med_id' est le bon champ
}
}
