<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Societe extends Model
{
    /** @use HasFactory<\Database\Factories\SocieteFactory> */
    use HasFactory;
    protected $fillable = ['nom','email','description','date_adhesion'];
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
