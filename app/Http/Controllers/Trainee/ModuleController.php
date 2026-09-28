<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\TraineeActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $trainee = Auth::user()->trainee;

        $myPrograms = $trainee->enrollments()
            ->whereIn('status', ['approved', 'completed'])
            ->with('program')
            ->get()
            ->pluck('program');

        $validIds = $myPrograms->pluck('id');
        $programId = (int) $request->query('program_id', $myPrograms->first()->id ?? 0);
        if (! $validIds->contains($programId) && $myPrograms->isNotEmpty()) {
            $programId = $myPrograms->first()->id;
        }

        $modules = collect();
        if ($programId) {
            $modules = Module::where('program_id', $programId)
                ->where('is_visible', true)
                ->orderBy('order_num')
                ->get();
        }

        return view('trainee.modules.index', compact('myPrograms', 'programId', 'modules'));
    }

    public function show(Module $module)
    {
        $trainee = Auth::user()->trainee;

        if (! $module->is_visible) {
            return redirect()->route('trainee.modules.index')->with('error', 'Module not found or not currently visible.');
        }

        $enrolled = $trainee->enrollments()
            ->where('program_id', $module->program_id)
            ->whereIn('status', ['approved', 'completed'])
            ->exists();

        if (! $enrolled) {
            return redirect()->route('trainee.modules.index')->with('error', 'You are not enrolled in this program.');
        }

        // Log the view once per session per module, to avoid spamming the activity log.
        $logKey = 'viewed_module_' . $module->id;
        if (! session()->has($logKey)) {
            TraineeActivity::create([
                'trainee_id' => $trainee->id,
                'program_id' => $module->program_id,
                'module_id' => $module->id,
                'activity_type' => 'Module Viewed',
                'description' => $module->title,
            ]);
            session()->put($logKey, true);
        }

        $ext = strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION));

        return view('trainee.modules.show', compact('module', 'ext'));
    }

    /**
     * Streams the module file inline (no forced download) after verifying the
     * trainee is approved for the program and the module is currently visible.
     */
    public function stream(Module $module)
    {
        $trainee = Auth::user()->trainee;

        if (! $module->is_visible) {
            abort(404);
        }

        $enrolled = $trainee->enrollments()
            ->where('program_id', $module->program_id)
            ->whereIn('status', ['approved', 'completed'])
            ->exists();

        if (! $enrolled) {
            abort(403);
        }

        $path = Storage::disk('local')->path('modules/' . $module->file_path);
        if (! file_exists($path)) {
            abort(404);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimes = [
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return response()->file($path, [
            'Content-Type' => $mimes[$ext] ?? 'application/octet-stream',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
