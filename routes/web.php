<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\TraineeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CertificateVerifyController;
use App\Http\Controllers\NotificationAjaxController;
use App\Http\Controllers\Trainee\ActivityController as TraineeActivityController;
use App\Http\Controllers\Trainee\CertificateController as TraineeCertificateController;
use App\Http\Controllers\Trainee\DashboardController as TraineeDashboardController;
use App\Http\Controllers\Trainee\EnrollController;
use App\Http\Controllers\Trainee\ExamController as TraineeExamController;
use App\Http\Controllers\Trainee\ModuleController as TraineeModuleController;
use App\Http\Controllers\Trainee\MyEnrollmentController;
use App\Http\Controllers\Trainee\NotificationController as TraineeNotificationController;
use App\Http\Controllers\Trainee\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Landing ─────────────────────────────────────────────────────
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->isAdminOrTrainer() ? 'admin.dashboard' : 'trainee.dashboard');
    }
    return redirect()->route('login');
})->name('landing');

// ── Auth ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Public certificate verification (no login) ──────────────────
Route::get('/verify-certificate/{code?}', [CertificateVerifyController::class, 'show'])->name('certificate.verify');

// ── Notification "mark all read" (any logged-in role) ────────────
Route::post('/ajax/notifications/mark-all-read', [NotificationAjaxController::class, 'markAllRead'])
    ->middleware('auth')
    ->name('ajax.notifications.markAllRead');

// ── Admin / Trainer ───────────────────────────────────────────────
Route::middleware(['auth', 'active', 'role:admin,trainer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/trainees', [TraineeController::class, 'index'])->name('trainees.index');
    Route::get('/trainees/{trainee}', [TraineeController::class, 'show'])->name('trainees.show');
    Route::post('/trainees/{user}/status/{action}', [TraineeController::class, 'updateStatus'])->name('trainees.status');

    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments/{enrollment}/{action}', [EnrollmentController::class, 'updateStatus'])->name('enrollments.status');

    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::delete('/programs/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');

    Route::get('/modules', [AdminModuleController::class, 'picker'])->name('modules.picker');
    Route::get('/modules/manage', [AdminModuleController::class, 'manage'])->name('modules.manage');
    Route::post('/modules', [AdminModuleController::class, 'store'])->name('modules.store');
    Route::post('/modules/{module}/toggle', [AdminModuleController::class, 'toggleVisibility'])->name('modules.toggle');
    Route::delete('/modules/{module}', [AdminModuleController::class, 'destroy'])->name('modules.destroy');

    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');

    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');

    // ── Exams (Admin / Trainer) ──────────────────────────────────
    Route::get('/exams', [AdminExamController::class, 'index'])->name('exams.index');
    Route::post('/exams', [AdminExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}/edit', [AdminExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [AdminExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [AdminExamController::class, 'destroy'])->name('exams.destroy');
    Route::post('/exams/{exam}/questions', [AdminExamController::class, 'storeQuestion'])->name('exams.questions.store');
    Route::delete('/exam-questions/{question}', [AdminExamController::class, 'destroyQuestion'])->name('exams.questions.destroy');
    Route::get('/exams/{exam}/results', [AdminExamController::class, 'results'])->name('exams.results');
});

// ── Trainee ───────────────────────────────────────────────────────
Route::middleware(['auth', 'active', 'role:trainee'])->prefix('trainee')->name('trainee.')->group(function () {
    Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');

    Route::get('/enroll', [EnrollController::class, 'index'])->name('enroll.index');
    Route::post('/enroll', [EnrollController::class, 'store'])->name('enroll.store');

    Route::get('/my-enrollments', [MyEnrollmentController::class, 'index'])->name('my_enrollments.index');

    Route::get('/modules', [TraineeModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}', [TraineeModuleController::class, 'show'])->name('modules.show');
    Route::get('/modules/{module}/stream', [TraineeModuleController::class, 'stream'])->name('modules.stream');

    Route::get('/activities', [TraineeActivityController::class, 'index'])->name('activities.index');
    Route::get('/certificates', [TraineeCertificateController::class, 'index'])->name('certificates.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/notifications', [TraineeNotificationController::class, 'index'])->name('notifications.index');

    // ── Exams (Trainee) ─────────────────────────────────────────
    Route::get('/exams', [TraineeExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/{exam}', [TraineeExamController::class, 'show'])->name('exams.show');
    Route::get('/exams/{exam}/take', [TraineeExamController::class, 'take'])->name('exams.take');
    Route::post('/exams/{exam}/submit', [TraineeExamController::class, 'submit'])->name('exams.submit');
    Route::get('/exam-attempts/{attempt}/result', [TraineeExamController::class, 'result'])->name('exams.result');
});
