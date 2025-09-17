<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProjectType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'user_count' => User::where('role', 'user')->count(),
            'admin_count' => User::where('role', 'admin')->count(),
            'vendor_count' => User::where('role', 'vendor')->count(),
            'total_projects' => 0, // Will be implemented when projects are created
            'total_vendors' => User::where('role', 'vendor')->count(),
            'total_interviews' => 0, // Will be implemented when interviews are created
        ];

        // Get recent users
        $recentUsers = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get project types for the dashboard
        $projectTypes = ProjectType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'projectTypes'));
    }
}