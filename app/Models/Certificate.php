<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = ['trainee_id', 'program_id', 'certificate_code', 'issued_date'];

    protected function casts(): array
    {
        return ['issued_date' => 'date'];
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
