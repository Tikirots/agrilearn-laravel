<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit()
    {
        $trainee = Auth::user()->trainee;

        return view('trainee.profile', compact('trainee'));
    }

    public function update(Request $request)
    {
        $trainee = Auth::user()->trainee;

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:150',
            'contact_number' => ['nullable', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'address' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('photo')) {
            // Remove the old photo if one existed
            if ($trainee->photo) {
                Storage::disk('public')->delete('photos/' . $trainee->photo);
            }
            $filename = 'trainee_' . Auth::id() . '_' . time() . '.' . $request->file('photo')->extension();
            $request->file('photo')->storeAs('photos', $filename, 'public');
            $data['photo'] = $filename;
        }

        $trainee->update($data);

        return redirect()->route('trainee.profile.edit')->with('success', 'Your profile has been saved. The admin can now view your details.');
    }
}
