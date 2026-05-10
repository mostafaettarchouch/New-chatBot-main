<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalProcedure extends Model
{
    use HasFactory;

    protected $fillable = ['legal_category_id', 'language_id', 'title', 'description', 'summary'];

    public function legalCategory()
    {
        return $this->belongsTo(LegalCategory::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function procedureSteps()
    {
        return $this->hasMany(ProcedureStep::class)->orderBy('step_number');
    }

    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}