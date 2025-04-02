<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande_examen extends Model
{
    protected $fillable = [
        'consultation_id',
        'liste_examens',
        'remarques'
    ];
    protected $casts = [
        'liste_examens' => 'array',
    ];
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
