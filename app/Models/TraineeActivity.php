<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraineeActivity extends Model
{
    use HasFactory;

    protected $table = 'trainee_activities';

    protected $fillable = ['trainee_id', 'program_id', 'module_id', 'activity_type', 'description', 'activity_date'];

    protected function casts(): array
    {
        return ['activity_date' => 'datetime'];
    }

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function program()
    {
        return $this->belongsTo(TrainingProgram::class, 'program_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
