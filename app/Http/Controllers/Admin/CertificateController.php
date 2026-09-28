<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $completed = Enrollment::where('status', 'completed')
            ->with(['trainee', 'program'])
            ->get()
            ->reject(function ($e) {
                return Certificate::where('trainee_id', $e->trainee_id)
                    ->where('program_id', $e->program_id)
                    ->exists();
            })
            ->sortBy(fn ($e) => $e->trainee->full_name);

        $certificates = Certificate::with(['trainee', 'program'])->latest()->get();

        return view('admin.certificates.index', compact('completed', 'certificates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'program_id' => 'required|exists:training_programs,id',
        ]);

        $exists = Certificate::where('trainee_id', $data['trainee_id'])
            ->where('program_id', $data['program_id'])
            ->exists();

        if ($exists) {
            return redirect()->route('admin.certificates.index')
                ->with('warning', 'A certificate for this trainee and program already exists.');
        }

        $code = generate_code('AGL');

        Certificate::create([
            'trainee_id' => $data['trainee_id'],
            'program_id' => $data['program_id'],
            'certificate_code' => $code,
            'issued_date' => now()->toDateString(),
        ]);

        return redirect()->route('admin.certificates.index')->with('success', "Certificate generated: {$code}");
    }
}
