<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['question_text', 'language_id', 'legal_procedure_id', 'asked_at'];

    protected $casts = [
        'asked_at' => 'datetime',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function legalProcedure()
    {
        return $this->belongsTo(LegalProcedure::class);
    }
}