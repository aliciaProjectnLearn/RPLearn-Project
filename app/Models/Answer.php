<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question_id', 'teacher_id', 'answer'];
    
    protected $hidden = ['question'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
