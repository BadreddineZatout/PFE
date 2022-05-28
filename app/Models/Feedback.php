<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date'
    ];


    protected $with = ['user', 'question', 'TypeFeedback'];

    public function getTitleAttribute()
    {
        return $this->question->question;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function TypeFeedback()
    {
        return $this->belongsTo(TypeFeedback::class);
    }
}
