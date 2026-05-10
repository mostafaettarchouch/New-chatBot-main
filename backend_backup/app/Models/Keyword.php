<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = ['legal_procedure_id', 'keyword', 'weight'];

    public function legalProcedure()
    {
        return $this->belongsTo(LegalProcedure::class);
    }
}