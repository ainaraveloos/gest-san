<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    protected $fillable = ['consultation_id','nbr_jours_repos'];
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
      public function medicaments()
    {
        return $this->hasMany(Medicament::class);
    }

}
