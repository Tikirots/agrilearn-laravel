<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['trainee_id', 'program_id', 'status', 'enrolled_at', 'decided_at'];

    protected function casts(): array
    {
        return ['enrolled_at' => 'datetime', 'decided_at' => 'datetime'];
    }

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function program()
    {
        return $this->belongsTo(TrainingProgram::class, 'program_id');
    }
}
