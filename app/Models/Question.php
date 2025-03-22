<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['text', 'test_id', 'points'];

    // A question belongs to a test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    // A question can have many answers
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}