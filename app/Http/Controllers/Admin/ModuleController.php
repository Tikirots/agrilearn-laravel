<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\TrainingProgram;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    private const ALLOWED_EXT = ['pdf', 'ppt', 'pptx', 'doc', 'docx', 'mp4'];

    /** Program picker — shown when no program_id is given. */
    public function picker()
    {
        $programs = TrainingProgram::withCount('modules')->latest()->get();

        return view('admin.modules.picker', compact('programs'));
    }

    public function manage(Request $request)
    {
        $programId = (int) $request->query('program_id', 0);
        if (! $programId) {
            return redirect()->route('admin.modules.picker');
        }

        $program = TrainingProgram::findOrFail($programId);
        $modules = $program->modules; // already ordered by order_num

        return view('admin.modules.manage', compact('program', 'modules'));
    }

    public function store(Request $request)
    {
        $programId = (int) $request->input('program_id');
        $program = TrainingProgram::findOrFail($programId);

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'order_num' => 'nullable|integer',
            'module_file' => 'required|file|mimes:pdf,ppt,pptx,doc,docx,mp4',
        ]);

        $file = $request->file('module_file');
        $ext = strtolower($file->getClientOriginalExtension());
        $safeName = uniqid('mod_') . '.' . $ext;

        Storage::disk('local')->putFileAs('modules', $file, $safeName);

        Module::create([
            'program_id' => $program->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_path' => $safeName,
            'order_num' => $data['order_num'] ?? 1,
            'is_visible' => false,
        ]);

        return redirect()->route('admin.modules.manage', ['program_id' => $program->id])
            ->with('success', 'Module uploaded. It is hidden by default — toggle visibility when ready.');
    }

    public function toggleVisibility(Module $module)
    {
        $wasHidden = ! $module->is_visible;
        $module->update(['is_visible' => ! $module->is_visible]);

        if ($wasHidden) {
            NotificationService::notifyModulePublished($module->program_id, $module->title, Auth::id());
            $message = 'Module released — trainees have been notified.';
        } else {
            $message = 'Module hidden from trainees.';
        }

        return redirect()->route('admin.modules.manage', ['program_id' => $module->program_id])->with('success', $message);
    }

    public function destroy(Module $module)
    {
        Storage::disk('local')->delete('modules/' . $module->file_path);
        $programId = $module->program_id;
        $module->delete();

        return redirect()->route('admin.modules.manage', ['program_id' => $programId])->with('success', 'Module deleted.');
    }
}
