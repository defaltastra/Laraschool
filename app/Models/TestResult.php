<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'user_id',
        'score',
        'total_points', 
    ];

    /**
     * Relationship: A test result belongs to a test.
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Relationship: A test result belongs to a student.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
