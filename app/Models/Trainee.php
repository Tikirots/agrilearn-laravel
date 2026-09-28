<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'full_name', 'address', 'contact_number', 'birthdate', 'gender', 'photo'];

    protected function casts(): array
    {
        return ['birthdate' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activities()
    {
        return $this->hasMany(TraineeActivity::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
