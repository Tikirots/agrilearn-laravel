<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['program_id', 'title', 'description', 'file_path', 'order_num', 'is_visible', 'uploaded_at'];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean', 'uploaded_at' => 'datetime'];
    }

    public function program()
    {
        return $this->belongsTo(TrainingProgram::class, 'program_id');
    }

    public function exam()
    {
        return $this->hasOne(Exam::class);
    }
}
