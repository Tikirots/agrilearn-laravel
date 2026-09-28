<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TrainingProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Account
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@agrilearn.local',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Sample Training Program
        TrainingProgram::updateOrCreate(
            ['title' => 'Organic Agriculture Production NC II'],
            [
                'nc_level' => 'NC II',
                'description' => 'Comprehensive training course covering organic concoctions, soil preparation, and crop management.',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'slots' => 30,
                'status' => 'open',
            ]
        );
    }
}
