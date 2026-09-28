<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\TrainingProgram;
use App\Models\User;

/**
 * Mirrors includes/notifications.php from the old app.
 */
class NotificationService
{
    public static function create(int $userId, string $type, string $title, ?string $message = null, ?string $link = null): void
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);
    }

    /**
     * Notify every approved trainee in a program that a module was released,
     * and log a confirmation notification for the admin who released it.
     */
    public static function notifyModulePublished(int $programId, string $moduleTitle, int $adminUserId): void
    {
        $program = TrainingProgram::find($programId);
        $programTitle = $program->title ?? 'your program';

        $traineeUserIds = $program->enrollments()
            ->where('status', 'approved')
            ->with('trainee.user')
            ->get()
            ->pluck('trainee.user.id')
            ->filter()
            ->values();

        $link = route('trainee.modules.index', ['program_id' => $programId]);

        foreach ($traineeUserIds as $uid) {
            self::create(
                $uid,
                'module_released',
                'New module released',
                "\"$moduleTitle\" is now available in $programTitle.",
                $link
            );
        }

        self::create(
            $adminUserId,
            'module_released_confirm',
            'Module published',
            "\"$moduleTitle\" was released to " . $traineeUserIds->count() . " trainee(s) in $programTitle.",
            route('admin.modules.manage', ['program_id' => $programId])
        );
    }

    /**
     * Notify every admin/trainer that a new trainee registration needs approval.
     */
    public static function notifyAdminsNewRegistration(int $traineeId, string $traineeName): void
    {
        $adminIds = User::whereIn('role', ['admin', 'trainer'])->pluck('id');

        $link = route('admin.trainees.show', $traineeId);

        foreach ($adminIds as $adminId) {
            self::create(
                $adminId,
                'trainee_registered',
                'New registration pending approval',
                "$traineeName just registered and is waiting for approval.",
                $link
            );
        }
    }

    /**
     * Notify a trainee that their account was approved or rejected.
     */
    public static function notifyTraineeApprovalStatus(int $userId, bool $approved): void
    {
        self::create(
            $userId,
            $approved ? 'account_approved' : 'account_rejected',
            $approved ? 'Your account was approved' : 'Registration not approved',
            $approved
                ? 'You can now log in and enroll in a training program.'
                : 'Your registration was not approved. Please contact the training center for details.',
            $approved ? route('login') : null
        );
    }

    public static function unreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)->where('is_read', false)->count();
    }

    public static function recent(int $userId, int $limit = 8)
    {
        return Notification::where('user_id', $userId)->latest()->limit($limit)->get();
    }

    public static function markAllRead(int $userId): void
    {
        Notification::where('user_id', $userId)->where('is_read', false)->update(['is_read' => true]);
    }
}
