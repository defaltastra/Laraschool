<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = ['name', 'description', 'teacher_id'];

    // A test can have many questions
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // A test belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
