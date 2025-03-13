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
        'user_id',
        'nom',
        'specialite',
        'type',
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

}
