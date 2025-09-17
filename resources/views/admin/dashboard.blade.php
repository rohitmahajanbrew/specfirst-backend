@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number" id="totalUsers">{{ $stats['total_users'] ?? 0 }}</div>
                        <div class="stats-label">Total Users</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number" id="totalProjects">{{ $stats['total_projects'] ?? 0 }}</div>
                        <div class="stats-label">Active Projects</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number" id="totalVendors">{{ $stats['total_vendors'] ?? 0 }}</div>
                        <div class="stats-label">Registered Vendors</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number" id="totalInterviews">{{ $stats['total_interviews'] ?? 0 }}</div>
                        <div class="stats-label">AI Interviews</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Users -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Recent Users
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->company_name }}</small>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge role-badge role-{{ $user->role }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge status-badge status-{{ $user->onboarding_completed ? 'active' : 'pending' }}">
                                        {{ $user->onboarding_completed ? 'Active' : 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <div>No users found</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                        View All Users <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    {{-- <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Add New User
                    </a> --}}
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-project-diagram me-2"></i>View Projects
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-store me-2"></i>Manage Vendors
                    </a>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-chart-bar me-2"></i>View Analytics
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>User Registration Trend
                </h5>
            </div>
            <div class="card-body">
                <canvas id="userRegistrationChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>User Roles Distribution
                </h5>
            </div>
            <div class="card-body">
                <canvas id="userRolesChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Professional Badge Styling */
.badge {
    font-weight: 500;
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

/* Role Badges */
.role-badge.role-admin {
    background-color: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.role-badge.role-user {
    background-color: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.role-badge.role-vendor {
    background-color: #fffbeb;
    color: #d97706;
    border: 1px solid #fed7aa;
}

/* Status Badges */
.status-badge.status-active {
    background-color: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.status-badge.status-pending {
    background-color: #f8fafc;
    color: #64748b;
    border: 1px solid #e2e8f0;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // User Registration Chart
    const userRegCtx = document.getElementById('userRegistrationChart').getContext('2d');
    new Chart(userRegCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Users',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // User Roles Chart
    const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
    new Chart(userRolesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Users', 'Admins', 'Vendors'],
            datasets: [{
                data: [{{ $stats['user_count'] ?? 0 }}, {{ $stats['admin_count'] ?? 0 }}, {{ $stats['vendor_count'] ?? 0 }}],
                backgroundColor: ['#6366f1', '#ef4444', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Animate stats numbers
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            element.textContent = current;
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Animate stats on page load
    setTimeout(() => {
        animateValue(document.getElementById('totalUsers'), 0, {{ $stats['total_users'] ?? 0 }}, 1000);
        animateValue(document.getElementById('totalProjects'), 0, {{ $stats['total_projects'] ?? 0 }}, 1000);
        animateValue(document.getElementById('totalVendors'), 0, {{ $stats['total_vendors'] ?? 0 }}, 1000);
        animateValue(document.getElementById('totalInterviews'), 0, {{ $stats['total_interviews'] ?? 0 }}, 1000);
    }, 500);
});
</script>
@endsection
