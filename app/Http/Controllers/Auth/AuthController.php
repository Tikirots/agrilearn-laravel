<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Trainee;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['username' => 'Invalid username or password.'])->withInput();
        }

        if ($user->status === 'pending') {
            return back()->withErrors(['username' => 'Your registration is still pending admin approval. Please check back once the training center approves your account.']);
        }

        if ($user->status === 'inactive') {
            return back()->withErrors(['username' => 'Your account is inactive or was not approved. Please contact the training center.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectToDashboard();
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:120|unique:users,email',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:30',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $trainee = DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['full_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'trainee',
                'status' => 'pending', // requires admin approval before login
            ]);

            return Trainee::create([
                'user_id' => $user->id,
                'full_name' => $data['full_name'],
                'address' => $data['address'] ?? null,
                'contact_number' => $data['contact_number'] ?? null,
                'birthdate' => $data['birthdate'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);
        });

        NotificationService::notifyAdminsNewRegistration($trainee->id, $trainee->full_name);

        return redirect()->route('login')
            ->with('success', 'Registration submitted! Your account is now pending review. You will be able to log in once the training center approves it.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectToDashboard()
    {
        $user = Auth::user();

        return redirect()->route($user->isAdminOrTrainer() ? 'admin.dashboard' : 'trainee.dashboard');
    }
}
