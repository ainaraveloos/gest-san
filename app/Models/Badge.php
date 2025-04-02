<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'patient_id',
        'numero',
        'qr_code',
        'date_emission',
        'validite',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
