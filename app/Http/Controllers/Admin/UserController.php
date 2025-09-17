<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('onboarding_completed', true);
            } elseif ($request->status === 'pending') {
                $query->where('onboarding_completed', false);
            }
        }

        // Sort
        $sort = $request->get('sort', 'created_at_desc');
        switch ($sort) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $projectTypes = ProjectType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.users.create', compact('projectTypes'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin,vendor',
            'preferred_project_types' => 'nullable|array',
            'preferred_project_types.*' => 'string|exists:project_types,slug',
            'device_type' => 'nullable|in:web,mobile,tablet',
            'onboarding_completed' => 'boolean',
            'email_verified' => 'boolean',
            'completed_step' => 'nullable|string',
            'next_step' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
            'role' => $request->role,
            'preferred_project_types' => $request->preferred_project_types ?? [],
            'device_type' => $request->device_type ?? 'web',
            'onboarding_completed' => $request->boolean('onboarding_completed'),
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
            'completed_step' => $request->completed_step,
            'next_step' => $request->next_step,
        ]);

        // Assign role using Spatie
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $projectTypes = ProjectType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.users.show', compact('user', 'projectTypes'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $projectTypes = ProjectType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.users.edit', compact('user', 'projectTypes'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'company_name' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin,vendor',
            'preferred_project_types' => 'nullable|array',
            'preferred_project_types.*' => 'string|exists:project_types,slug',
            'device_type' => 'nullable|in:web,mobile,tablet',
            'onboarding_completed' => 'boolean',
            'email_verified' => 'boolean',
            'completed_step' => 'nullable|string',
            'next_step' => 'nullable|string',
        ]);

        $updateData = [
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'role' => $request->role,
            'preferred_project_types' => $request->preferred_project_types ?? [],
            'device_type' => $request->device_type ?? 'web',
            'onboarding_completed' => $request->boolean('onboarding_completed'),
            'completed_step' => $request->completed_step,
            'next_step' => $request->next_step,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update email verification
        if ($request->boolean('email_verified')) {
            $updateData['email_verified_at'] = now();
        } else {
            $updateData['email_verified_at'] = null;
        }

        $user->update($updateData);

        // Update Spatie role
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $userIds = $request->user_ids;
        
        // Prevent deleting the current admin user
        $userIds = array_filter($userIds, function ($id) {
            return $id != auth()->id();
        });

        if (empty($userIds)) {
            return response()->json(['error' => 'No valid users to delete.'], 400);
        }

        User::whereIn('id', $userIds)->delete();

        return response()->json(['success' => 'Users deleted successfully.']);
    }
}