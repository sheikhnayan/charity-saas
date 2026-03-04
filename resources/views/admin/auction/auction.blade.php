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
                                          {{ $website->name }}  Auction
                                        </span>
                                    </div>

                                </div>
                                <div class="page-title-actions">

                                    <a href="/admins/auction/add/{{ $website->id }}" class="btn btn-success">Add</a>

                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb" style="float: left">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="/admins/auction">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Auction</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            Setting
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            Auction
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <form id="filterForm" class="d-flex align-items-center gap-3">
                                            <label for="statusFilter" class="form-label mb-0">Filter by Status:</label>
                                            <select id="statusFilter" class="form-select" style="width: auto;">
                                                <option value="">All Auctions</option>
                                                <option value="1">Enabled Only</option>
                                                <option value="0">Disabled Only</option>
                                                <option value="2">Archived Only</option>
                                            </select>
                                            <button type="button" onclick="clearFilter()" class="btn btn-outline-secondary btn-sm">
                                                Clear Filter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary" style="background: #fff !important;">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>SI</th>
                                                <th>Title</th>
                                                <th>Domain</th>
                                                <th>Deadline</th>
                                                <th>Value</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->isEmpty())
                                                <tr>
                                                    <td colspan="7" class="text-center">No data found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item->title }}</td>
                                                        <td>{{ $item->website->domain }}</td>
                                                        <td>{{ $item->dead_line }}</td>
                                                        <td>${{ $item->value }}</td>
                                                        <td>
                                                            @if ($item->status == 1)
                                                                <span class="badge bg-success">Enabled</span>
                                                            @elseif ($item->status == 0)
                                                                <span class="badge bg-secondary">Disabled</span>
                                                            @elseif ($item->status == 2)
                                                                <span class="badge bg-warning">Archived</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="/admins/auction-edit/{{ $item->id }}" class="btn btn-primary btn-sm">Edit</a>
                                                            
                                                            <button onclick="viewBids({{ $item->id }}, '{{ $item->title }}')" class="btn btn-info btn-sm">
                                                                <i class="fas fa-gavel"></i> View Bids
                                                            </button>
                                                            
                                                            @if ($item->status != 2)
                                                                <button onclick="archiveAuction({{ $item->id }})" class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-archive"></i> Archive
                                                                </button>
                                                            @else
                                                                <button onclick="unarchiveAuction({{ $item->id }})" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-undo"></i> Unarchive
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
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
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

            <script>
                let table;
                
                $(document).ready(function() {
                    // Initialize DataTable
                    table = new DataTable('.table', {
                        pageLength: 25
                    });

                    // Add filter functionality
                    $('#statusFilter').on('change', function() {
                        let filterValue = this.value;
                        if (filterValue === '') {
                            table.column(5).search('').draw(); // Column 5 is the Status column
                        } else {
                            let searchTerm = '';
                            switch(filterValue) {
                                case '1': searchTerm = 'Enabled'; break;
                                case '0': searchTerm = 'Disabled'; break;
                                case '2': searchTerm = 'Archived'; break;
                            }
                            table.column(5).search(searchTerm).draw();
                        }
                    });
                });

                function archiveAuction(auctionId) {
                    if (confirm('Are you sure you want to archive this auction?')) {
                        updateAuctionStatus(auctionId, 2, 'Auction archived successfully!');
                    }
                }

                function unarchiveAuction(auctionId) {
                    if (confirm('Are you sure you want to unarchive this auction?')) {
                        updateAuctionStatus(auctionId, 1, 'Auction unarchived successfully!');
                    }
                }

                function updateAuctionStatus(auctionId, status, message) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '/admins/auction/update-status/' + auctionId,
                        method: 'POST',
                        data: {
                            status: status
                        },
                        success: function(response) {
                            alert(message);
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error updating auction status. Please try again.');
                        }
                    });
                }

                function clearFilter() {
                    $('#statusFilter').val('');
                    table.column(5).search('').draw();
                }
            </script>

            <!-- Bids Modal -->
            <div class="modal fade" id="bidsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-gavel me-2"></i> Live Bids - <span id="auctionTitle"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="bidsContainer" style="max-height: 500px; overflow-y: auto;">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="exportBidsToCSV()">
                                <i class="fas fa-download me-1"></i> Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            let currentBidsData = [];
            let currentAuctionTitle = '';

            function viewBids(auctionId, auctionTitle) {
                currentAuctionTitle = auctionTitle;
                document.getElementById('auctionTitle').textContent = auctionTitle;
                document.getElementById('bidsContainer').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                
                const modal = new bootstrap.Modal(document.getElementById('bidsModal'));
                modal.show();

                // Fetch bids for this auction
                fetch(`/admins/auction/${auctionId}/bids`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentBidsData = data.bids;
                        displayBids(data.bids);
                    } else {
                        document.getElementById('bidsContainer').innerHTML = '<div class="alert alert-danger">Error loading bids: ' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('bidsContainer').innerHTML = '<div class="alert alert-danger">Error loading bids</div>';
                });
            }

            function displayBids(bids) {
                if (bids.length === 0) {
                    document.getElementById('bidsContainer').innerHTML = '<div class="alert alert-info text-center"><i class="fas fa-inbox me-2"></i> No bids yet</div>';
                    return;
                }

                let html = `
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 4%;">Rank</th>
                                    <th style="width: 16%;">Bidder Name</th>
                                    <th style="width: 18%;">Email</th>
                                    <th style="width: 12%;">Bid Amount</th>
                                    <th style="width: 15%;">Location</th>
                                    <th style="width: 12%;">Phone</th>
                                    <th style="width: 14%;">Time</th>
                                    <th style="width: 9%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                bids.forEach((bid, index) => {
                    const isHighest = index === 0 ? 'table-success' : '';
                    const timestamp = new Date(bid.created_at).toLocaleString();
                    const status = index === 0 ? '<span class="badge bg-success"><i class="fas fa-crown me-1"></i> Highest</span>' : '<span class="badge bg-secondary">Bid #' + (index + 1) + '</span>';
                    const location = `${bid.city || '-'}, ${bid.state || '-'}`;
                    const phone = bid.phone ? `<a href="tel:${bid.phone}">${bid.phone}</a>` : '-';
                    
                    html += `
                        <tr class="${isHighest}">
                            <td><strong>#${index + 1}</strong></td>
                            <td>${escapeHtml(bid.name || '-')}</td>
                            <td><a href="mailto:${escapeHtml(bid.email)}" title="${escapeHtml(bid.email)}">${escapeHtml(bid.email)}</a></td>
                            <td><strong style="color: ${index === 0 ? '#28a745' : '#000'};">$${parseFloat(bid.amount).toFixed(2)}</strong></td>
                            <td><small>${location}</small></td>
                            <td>${phone}</td>
                            <td><small class="text-muted">${timestamp}</small></td>
                            <td>${status}</td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3">
                        <strong><i class="fas fa-info-circle me-2"></i>Auction Bids Summary:</strong>
                        <br>Total Bids: <strong>${bids.length}</strong>
                        <br>Highest Bid: <strong>$${parseFloat(bids[0].amount).toFixed(2)}</strong>
                        <br>Lowest Bid: <strong>$${parseFloat(bids[bids.length - 1].amount).toFixed(2)}</strong>
                        <br>Average Bid: <strong>$${(bids.reduce((sum, bid) => sum + parseFloat(bid.amount), 0) / bids.length).toFixed(2)}</strong>
                    </div>
                `;

                document.getElementById('bidsContainer').innerHTML = html;
            }

            function escapeHtml(text) {
                if (!text) return '';
                return text.replace(/[&<>"']/g, function(m) {
                    return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[m];
                });
            }

            function exportBidsToCSV() {
                if (currentBidsData.length === 0) {
                    alert('No bids to export');
                    return;
                }

                let csv = 'Rank,Bidder Name,Email,Phone,City,State,Zip,Address,Bid Amount,Timestamp,Transaction ID,Status\n';
                currentBidsData.forEach((bid, index) => {
                    csv += `${index + 1},"${bid.name || ''}","${bid.email || ''}","${bid.phone || ''}","${bid.city || ''}","${bid.state || ''}","${bid.zip || ''}","${bid.address || ''}",${bid.amount},"${bid.created_at}","${bid.transaction_id || ''}","${bid.status === 1 ? 'Completed' : 'Pending'}"\n`;
                });

                const element = document.createElement('a');
                element.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv));
                element.setAttribute('download', `auction-bids-${currentAuctionTitle}-${Date.now()}.csv`);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            }
            </script>
@endsection