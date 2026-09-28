<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TraineeApprovedMail;
use App\Mail\TraineeRejectedMail;
use App\Models\Trainee;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TraineeController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));

        $query = Trainee::query()
            ->join('users', 'users.id', '=', 'trainees.user_id')
            ->with('user')
            ->withCount(['enrollments as active_programs_count' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->select('trainees.*');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('trainees.full_name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $trainees = $query
            ->orderByRaw("(users.status = 'pending') DESC")
            ->orderByDesc('trainees.created_at')
            ->get();

        return view('admin.trainees.index', compact('trainees', 'search'));
    }

    public function show(Trainee $trainee)
    {
        $trainee->load('user');
        $enrollments = $trainee->enrollments()->with('program')->get();
        $activities = $trainee->activities()->with('program')->latest('activity_date')->limit(20)->get();

        return view('admin.trainees.show', compact('trainee', 'enrollments', 'activities'));
    }

    public function updateStatus(Request $request, User $user, string $action)
    {
        $newStatus = match ($action) {
            'approve', 'reactivate' => 'active',
            'reject' => 'inactive',
            default => null,
        };

        if ($newStatus && $user->role === 'trainee') {
            $user->update(['status' => $newStatus]);

            if (in_array($action, ['approve', 'reject'])) {
                NotificationService::notifyTraineeApprovalStatus($user->id, $action === 'approve');

                try {
                    if ($action === 'approve') {
                        Mail::to($user->email)->send(new TraineeApprovedMail($user));
                    } else {
                        Mail::to($user->email)->send(new TraineeRejectedMail($user));
                    }
                } catch (\Throwable $e) {
                    Log::error("Failed to send approval email to {$user->email}: " . $e->getMessage());
                }
            }
        }

        return redirect()
            ->route('admin.trainees.index')
            ->with('success', 'Account status updated.');
    }
}
