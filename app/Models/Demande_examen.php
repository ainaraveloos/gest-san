<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande_examen extends Model
{
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
