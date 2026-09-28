<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->constrained('training_programs')->cascadeOnDelete();
            $table->string('certificate_code', 40)->unique();
            $table->date('issued_date');
            $table->timestamps();

            $table->unique(['trainee_id', 'program_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
