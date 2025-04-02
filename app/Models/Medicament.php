<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicament extends Model
{
    /** @use HasFactory<\Database\Factories\MedicamentFactory> */
    use HasFactory;
    protected $fillable = ['ordonnance_id','designation','forme','posologie','quantite'];
}
