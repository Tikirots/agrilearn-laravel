<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'trainee_id',
        'score',
        'total_questions',
        'passed',
        'answers_json',
        'date_taken',
    ];

    protected function casts(): array
    {
        return [
            'answers_json' => 'array',
            'passed' => 'boolean',
            'date_taken' => 'datetime',
        ];
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }
}
