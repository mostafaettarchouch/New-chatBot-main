<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedureStep extends Model
{
    use HasFactory;

    protected $fillable = ['legal_procedure_id', 'step_number', 'title', 'description', 'requirements', 'documents_needed'];

    public function legalProcedure()
    {
        return $this->belongsTo(LegalProcedure::class);
    }
}