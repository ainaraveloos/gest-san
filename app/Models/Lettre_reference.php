<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lettre_reference extends Model
{
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
