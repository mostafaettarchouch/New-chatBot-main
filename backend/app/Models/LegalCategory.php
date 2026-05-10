<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function legalProcedures()
    {
        return $this->hasMany(LegalProcedure::class);
    }
}
