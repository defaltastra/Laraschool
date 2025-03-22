<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['text', 'question_id', 'is_correct'];

    // An answer belongs to a question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}