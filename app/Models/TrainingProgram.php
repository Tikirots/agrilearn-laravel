<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'nc_level', 'description', 'start_date', 'end_date', 'slots', 'status'];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'program_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'program_id')->orderBy('order_num');
    }

    public function activities()
    {
        return $this->hasMany(TraineeActivity::class, 'program_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'program_id');
    }

    public function enrolledCount(): int
    {
        return $this->enrollments()->where('status', 'approved')->count();
    }
}
