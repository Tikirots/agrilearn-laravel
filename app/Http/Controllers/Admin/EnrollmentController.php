<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('status', 'pending');

        $query = Enrollment::with(['trainee', 'program']);
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $enrollments = $query->latest('enrolled_at')->get();

        return view('admin.enrollments.index', compact('enrollments', 'filter'));
    }

    public function updateStatus(Request $request, Enrollment $enrollment, string $action)
    {
        $status = match ($action) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'complete' => 'completed',
            default => null,
        };

        if ($status) {
            $enrollment->update(['status' => $status, 'decided_at' => now()]);
        }

        return redirect()->route('admin.enrollments.index')->with('success', "Enrollment marked as {$status}.");
    }
}
