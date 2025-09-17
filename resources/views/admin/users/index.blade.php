@extends('layouts.admin')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h5 class="mb-0">All Users</h5>
        <p class="text-muted">Manage user accounts and permissions</p>
    </div>
    <div class="col-md-6 text-end">
        {{-- <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New User
        </a> --}}
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="roleFilter" class="form-label">Filter by Role</label>
                <select class="form-select" id="roleFilter">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="vendor">Vendor</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="statusFilter" class="form-label">Filter by Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
                    <div class="col-md-3">
                        <label for="companyFilter" class="form-label">Filter by Company</label>
                        <select class="form-select" id="companyFilter">
                            <option value="">All Companies</option>
                            <option value="SpecFirst">SpecFirst</option>
                            <option value="Test Company">Test Company</option>
                            <option value="Vendor Solutions Inc">Vendor Solutions Inc</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dateFilter" class="form-label">Filter by Joined Date</label>
                        <select class="form-select" id="dateFilter">
                            <option value="">All Time</option>
                            <optgroup label="This Week">
                                <option value="this_week">This Week</option>
                            </optgroup>
                            <optgroup label="This Month">
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                            </optgroup>
                            <optgroup label="This Year">
                                <option value="this_year">This Year</option>
                                <option value="last_year">Last Year</option>
                            </optgroup>
                            <optgroup label="Custom Range">
                                <option value="last_3_months">Last 3 Months</option>
                                <option value="last_6_months">Last 6 Months</option>
                                <option value="last_12_months">Last 12 Months</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </button>
                    </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="usersTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Company</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->full_name }}</small>
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
                        <td>{{ $user->company_name ?? 'N/A' }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                            <i class="fas fa-eye me-2"></i>View
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li> --}}
                                    {{-- <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li> --}}
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bulk Actions -->
<div class="card mt-4" id="bulkActions" style="display: none;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span id="selectedCount">0</span> users selected
            </div>
            <div>
                <button class="btn btn-outline-danger me-2" id="bulkDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Delete Selected
                </button>
                <button class="btn btn-outline-primary" id="bulkActivateBtn">
                    <i class="fas fa-check me-2"></i>Activate Selected
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* DataTables Professional Styling */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
    color: #6c757d;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_length select {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    margin: 0 0.125rem;
    color: #6c757d;
    background: white;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f8f9fa;
    border-color: #6366f1;
    color: #6366f1;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #6366f1;
    border-color: #6366f1;
    color: white;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    background: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
}

/* Export buttons styling */
.dt-buttons {
    margin-bottom: 1rem;
}

.dt-buttons .btn {
    margin-right: 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    color: white;
    font-weight: 500;
}

.dt-buttons .btn:hover {
    background: linear-gradient(135deg, #5b5bd6 0%, #7c3aed 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

/* Table styling */
#usersTable {
    border-collapse: separate;
    border-spacing: 0;
}

#usersTable thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 1rem 0.75rem;
}

#usersTable tbody td {
    padding: 0.75rem;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

#usersTable tbody tr:hover {
    background-color: #f8f9fa;
}

/* Filter section styling */
.card .card-body {
    padding: 1.5rem;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-select {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
}

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
    // Initialize DataTables
    var table = $('#usersTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        order: [[6, 'desc']], // Sort by Joined date (column 6) descending
        columnDefs: [
            { orderable: false, targets: [0, 7] }, // Disable sorting on checkbox and actions columns
            { searchable: false, targets: [0, 7] } // Disable search on checkbox and actions columns
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-download me-2"></i>Export Excel',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-download me-2"></i>Export PDF',
                className: 'btn btn-primary btn-sm'
            }
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        initComplete: function() {
            // Add custom filter functionality
            $('#roleFilter').on('change', function() {
                var role = $(this).val();
                if (role === '') {
                    table.column(3).search('').draw();
                } else {
                    // Search for the role text (case insensitive)
                    table.column(3).search(role, false, false).draw();
                }
            });

            $('#statusFilter').on('change', function() {
                var status = $(this).val();
                if (status === '') {
                    table.column(4).search('').draw();
                } else {
                    // Search for the status text (case insensitive)
                    table.column(4).search(status, false, false).draw();
                }
            });

                   $('#companyFilter').on('change', function() {
                       var company = $(this).val();
                       if (company === '') {
                           table.column(5).search('').draw();
                       } else {
                           table.column(5).search('^' + company + '$', true, false).draw();
                       }
                   });

                   $('#dateFilter').on('change', function() {
                       var dateFilter = $(this).val();
                       var now = new Date();
                       
                       // Clear any existing custom filters first
                       $.fn.dataTable.ext.search.pop();

                       if (dateFilter === '') {
                           table.column(6).search('').draw();
                           return;
                       }

                       // Apply custom filter for date ranges
                       $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                           if (settings.nTable.id !== 'usersTable') return true;
                           
                           var joinedDateStr = data[6]; // Column 6 is the joined date
                           if (!joinedDateStr) return false;
                           
                           // Parse the date string (format: "Jan 01, 2024")
                           var joinedDate = new Date(joinedDateStr);
                           if (isNaN(joinedDate.getTime())) return false;
                           
                           var today = new Date();
                           var startDate, endDate;
                           
                           switch(dateFilter) {
                               case 'this_week':
                                   startDate = new Date(today);
                                   startDate.setDate(today.getDate() - today.getDay());
                                   endDate = new Date(today);
                                   endDate.setDate(today.getDate() + (6 - today.getDay()));
                                   break;
                               case 'this_month':
                                   startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                                   endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                                   break;
                               case 'last_month':
                                   startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                                   endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                                   break;
                               case 'this_year':
                                   startDate = new Date(today.getFullYear(), 0, 1);
                                   endDate = new Date(today.getFullYear(), 11, 31);
                                   break;
                               case 'last_year':
                                   startDate = new Date(today.getFullYear() - 1, 0, 1);
                                   endDate = new Date(today.getFullYear() - 1, 11, 31);
                                   break;
                               case 'last_3_months':
                                   startDate = new Date(today.getFullYear(), today.getMonth() - 3, today.getDate());
                                   endDate = new Date(today);
                                   break;
                               case 'last_6_months':
                                   startDate = new Date(today.getFullYear(), today.getMonth() - 6, today.getDate());
                                   endDate = new Date(today);
                                   break;
                               case 'last_12_months':
                                   startDate = new Date(today.getFullYear(), today.getMonth() - 12, today.getDate());
                                   endDate = new Date(today);
                                   break;
                               default:
                                   return true;
                           }
                           
                           // Set time to start/end of day for accurate comparison
                           startDate.setHours(0, 0, 0, 0);
                           endDate.setHours(23, 59, 59, 999);
                           joinedDate.setHours(0, 0, 0, 0);
                           
                           return joinedDate >= startDate && joinedDate <= endDate;
                       });
                       
                       table.draw();
                   });

                   $('#clearFilters').on('click', function() {
                       $('#roleFilter, #statusFilter, #companyFilter, #dateFilter').val('');
                       // Clear all custom filters
                       $.fn.dataTable.ext.search.pop();
                       table.search('').columns().search('').draw();
                   });
        }
    });

    // Select all functionality
    $('#selectAll').change(function() {
        $('.user-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    $('.user-checkbox').change(function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        const selectedCount = $('.user-checkbox:checked').length;
        $('#selectedCount').text(selectedCount);
        
        if (selectedCount > 0) {
            $('#bulkActions').show();
            $('#selectAll').prop('indeterminate', selectedCount < $('.user-checkbox').length);
        } else {
            $('#bulkActions').hide();
            $('#selectAll').prop('indeterminate', false);
        }
    }

    // Bulk delete
    $('#bulkDeleteBtn').click(function() {
        const selectedIds = $('.user-checkbox:checked').map(function() {
            return this.value;
        }).get();

        if (selectedIds.length === 0) return;

        if (confirm(`Are you sure you want to delete ${selectedIds.length} users?`)) {
            showLoading();
            
            $.ajax({
                url: '{{ route("admin.users.bulk-delete") }}',
                method: 'POST',
                data: {
                    user_ids: selectedIds,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    hideLoading();
                    location.reload();
                },
                error: function() {
                    hideLoading();
                    alert('Error deleting users. Please try again.');
                }
            });
        }
    });

    // Update bulk actions when DataTables redraws
    table.on('draw', function() {
        updateBulkActions();
    });
});
</script>
@endsection
