@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

<style>
    .forms-wizard li.done em::before, .lnr-checkmark-circle::before {
  content: "\e87f";
}

.forms-wizard li.done em::before {
  display: block;
  font-size: 1.2rem;
  height: 42px;
  line-height: 40px;
  text-align: center;
  width: 42px;
}

.forms-wizard li.done em {
  font-family: Linearicons-Free;
}

.dt-buttons button span {
  color: #000 !important;
}

.paginate_buttons a {
  color: #000 !important;
}
</style>
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="app-main__inner">
                        <div class="app-page-title mt-4" data-step="" data-title="" data-intro="">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">

                                    <div class="page-title-icon">
                                        <i class="fas fa-id-card icon-gradient bg-arielle-smile"></i>
                                    </div>

                                    <div>
                                        <span class="text-capitalize">
                                            Users
                                        </span>
                                        <div class="page-title-subheading">
                                            View all Users.
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="#">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            Reports
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            users
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary p-4 card-border text-white mb-3 card bg-primary" style="background: #fff !important;">
                                    <div class="mb-3">
                                        <label for="websiteFilter" class="form-label">Filter by Website:</label>
                                        <select id="websiteFilter" class="form-select" style="max-width:300px;">
                                            <option value="">All Websites</option>
                                            @foreach($websites as $website)
                                                <option value="{{ $website->name }}">{{ $website->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Mass Approve Button -->
                                    <div class="mb-3">
                                        <button id="massApproveBtn" class="btn btn-success d-none" style="display: none;">
                                            <i class="fas fa-check-double"></i> Mass Approve Selected
                                        </button>
                                        <span id="selectedCount" class="ms-2 text-muted"></span>
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 40px;"><input type="checkbox" id="selectAll" title="Select All"></th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Website</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Teacher</th>
                                                <th>Goal</th>
                                                <th>Registration Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        {{-- @php
                                            dd($data);
                                        @endphp --}}
                                        <tbody>
                                            @if ($data->isEmpty())
                                                <tr>
                                                    <td colspan="11" class="text-center">No users found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data as $item)
                                                @if ($item->role != 'admin')
                                                    <tr>
                                                        <td>
                                                            @if ($item->status != 1)
                                                                <input type="checkbox" class="user-checkbox" value="{{ $item->id }}" data-user-email="{{ $item->email }}">
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->id }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.user.profile', $item->id) }}" class="text-decoration-none fw-bold text-primary">
                                                                {{ $item->name }} {{ $item->last_name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $item->website->name ?? 'N/A' }}</td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>
                                                            <span class="badge bg-info">{{ ucfirst($item->role) }}</span>
                                                        </td>
                                                        <td>{{ $item->teacher->name ?? 'N/A' }}</td>
                                                        <td>${{ number_format($item->goal ?? 0, 2) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d h:i A') }}</td>
                                                        <td>
                                                            @if ($item->status == 1)
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-warning">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.user.profile', $item->id) }}" class="btn btn-sm btn-primary me-1" title="View Profile">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if ($item->status != 1)
                                                                <a href="/admins/student/approve/{{ $item->id }}" class="btn btn-sm btn-success" title="Approve">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Include DataTables and jQuery CDN -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
            <style>
                .dataTables_wrapper .dataTables_paginate .paginate_button.current,
                .dataTables_wrapper .dataTables_paginate .paginate_button {
                    color: #000 !important;
                }
            </style>
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

            <!-- Loading Overlay for Mass Approval -->
            <div id="loadingOverlay" style="
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                z-index: 9999;
                justify-content: center;
                align-items: center;
            ">
                <div style="
                    background: white;
                    padding: 40px;
                    border-radius: 10px;
                    text-align: center;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                ">
                    <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #007bff; margin-bottom: 20px; display: block;"></i>
                    <h4 style="margin: 15px 0; color: #333;">Processing Approvals</h4>
                    <p style="color: #666; margin: 10px 0;">
                        <strong>Please do not refresh, close, or navigate away from this page.</strong>
                    </p>
                    <p style="color: #999; font-size: 14px; margin: 10px 0;" id="processingStatus">
                        Approving selected users...
                    </p>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    // Initialize DataTable with export/import buttons
                    let table = new DataTable('.table', {
                        dom: 'Bfrtip',
                        pageLength: 25,
                        scrollX: true,
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                    });
                    $('#websiteFilter').on('change', function() {
                        let val = $(this).val();
                        if(val) {
                            // Escape special regex characters
                            let escapedVal = val.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                            table.column(3).search('^' + escapedVal + '$', true, false).draw();
                        } else {
                            table.column(3).search('').draw();
                        }
                    });

                    // Mass Approval Functionality
                    let selectedIds = [];

                    // Select All / Deselect All
                    $('#selectAll').on('change', function() {
                        if($(this).is(':checked')) {
                            $('.user-checkbox:not(:checked)').each(function() {
                                $(this).prop('checked', true);
                            });
                        } else {
                            $('.user-checkbox:checked').each(function() {
                                $(this).prop('checked', false);
                            });
                        }
                        updateSelectedCount();
                    });

                    // Individual checkbox change
                    $(document).on('change', '.user-checkbox', function() {
                        updateSelectedCount();
                    });

                    // Update selected count display
                    function updateSelectedCount() {
                        selectedIds = [];
                        $('.user-checkbox:checked').each(function() {
                            selectedIds.push(parseInt($(this).val()));
                        });
                        
                        const count = selectedIds.length;
                        const countSpan = $('#selectedCount');
                        const massApproveBtn = $('#massApproveBtn');
                        
                        if(count > 0) {
                            countSpan.html('<span style="color: #28a745; font-weight: bold;">' + count + ' user(s) selected</span>');
                            massApproveBtn.removeClass('d-none').show();
                        } else {
                            countSpan.html('');
                            massApproveBtn.addClass('d-none').hide();
                        }

                        // Update Select All checkbox state
                        const totalCheckboxes = $('.user-checkbox').length;
                        const checkedCheckboxes = $('.user-checkbox:checked').length;
                        $('#selectAll').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
                    }

                    // Mass Approve Button Click
                    $('#massApproveBtn').on('click', function() {
                        if(selectedIds.length === 0) {
                            alert('Please select at least one user to approve.');
                            return;
                        }

                        if(!confirm('Are you sure you want to approve ' + selectedIds.length + ' user(s)?')) {
                            return;
                        }

                        // Show loading overlay
                        $('#loadingOverlay').css('display', 'flex');

                        // Send AJAX request
                        $.ajax({
                            url: '/admins/students/mass-approve',
                            type: 'POST',
                            contentType: 'application/json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: JSON.stringify({
                                user_ids: selectedIds
                            }),
                            success: function(response) {
                                $('#loadingOverlay').css('display', 'none');
                                
                                if(response.success) {
                                    alert('Successfully approved ' + response.approved + ' user(s)!');
                                    // Reload the page to reflect changes
                                    location.reload();
                                } else {
                                    alert('Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                $('#loadingOverlay').css('display', 'none');
                                console.error('Error:', error);
                                console.error('Response:', xhr.responseText);
                                alert('An error occurred while processing approvals. Please try again.');
                            }
                        });
                    });
                });

            </script>
        @endsection
