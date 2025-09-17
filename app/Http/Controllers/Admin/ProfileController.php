<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the admin profile page.
     */
    public function show()
    {
        $admin = auth('admin')->user();
        return view('admin.profile.show', compact('admin'));
    }

    /**
     * Update the admin profile information.
     */
    public function update(Request $request)
    {
        $admin = auth('admin')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'phone_number' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $admin->update([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
        ]);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the admin password.
     */
    public function updatePassword(Request $request)
    {
        $admin = auth('admin')->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password:admin',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'The current password is incorrect.',
            'password.required' => 'New password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'password');
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Password updated successfully!')
            ->with('active_tab', 'profile');
    }
}
