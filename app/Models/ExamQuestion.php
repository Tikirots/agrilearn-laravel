<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question_text',
        'type',
        'choices',
        'correct_answer',
    ];

    protected function casts(): array
    {
        return [
            'choices' => 'array',
        ];
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
