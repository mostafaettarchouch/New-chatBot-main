<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnansweredQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question_text', 'language_id', 'resolved', 'admin_notes', 'asked_at'];

    protected $casts = [
        'asked_at' => 'datetime',
        'resolved' => 'boolean',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
