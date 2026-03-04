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
                                            Change Password
                                        </span>
                                        <div class="page-title-subheading">
                                            Update your account password.
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                </div>
                            </div>

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

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
                                            Settings
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            Change Password
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary p-4" style="background: #fff !important;">
                                    <form action="/admins/change-password" method="post">
                                    @csrf
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label" style="color: #000;">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label" style="color: #000;">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        </div>

                                        <button type="submit" class="btn btn-success">Update Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- View Transaction Modal -->
            <div class="modal fade" id="viewDonationModal" tabindex="-1" aria-labelledby="viewDonationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDonationModalLabel">Transaction Details</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success btn-sm" id="downloadPdfBtn">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                        <button type="button" class="btn btn-info btn-sm" id="resendInvoiceBtn">
                            <i class="fas fa-envelope"></i> Resend Invoice
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="row">
                        <!-- Transaction Details Column -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Transaction Details</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Transaction ID:</strong> <span id="modal-transaction"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>IP Address:</strong> <span id="modal-ip-address"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>First Name:</strong> <span id="modal-first-name"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Last Name:</strong> <span id="modal-last-name"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Email:</strong> <span id="modal-email"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Phone:</strong> <span id="modal-phone"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Type:</strong> <span id="modal-type"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Status:</strong> <span id="modal-status"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Website ID:</strong> <span id="modal-website"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Date:</strong> <span id="modal-date"></span>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Payment Information Column -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-success">Payment Information</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment First Name:</strong> <span id="modal-payment-first-name"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment Last Name:</strong> <span id="modal-payment-last-name"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment Phone:</strong> <span id="modal-payment-phone"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment Email:</strong> <span id="modal-payment-email"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment Address:</strong> <span id="modal-payment-address"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment City:</strong> <span id="modal-payment-city"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment State:</strong> <span id="modal-payment-state"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment Country:</strong> <span id="modal-payment-country"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Payment Zip Code:</strong> <span id="modal-payment-zip"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total Amount:</strong> <span id="modal-total-amount"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total Due:</strong> <span id="modal-total-due"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Investment Information (Only shown for investment type) -->
                    <div class="row mt-4" id="investment-section" style="display: none;">
                        <div class="col-12">
                            <h6 class="mb-3 text-warning">Investment Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Investor Name:</strong> <span id="modal-investor-name"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Investor Email:</strong> <span id="modal-investor-email"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Investor Phone:</strong> <span id="modal-investor-phone"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Investor Type:</strong> <span id="modal-investor-type"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Share Quantity:</strong> <span id="modal-share-quantity"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Investment Amount:</strong> <span id="modal-investment-amount"></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <strong>Notes:</strong>
                                            <div id="modal-investment-notes" class="mt-2"></div>
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Investor Data:</strong>
                                            <div id="modal-investor-data" class="mt-2 small"></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Financial Details -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3 text-info">Financial Breakdown</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Gross Amount:</strong> <span id="modal-gross"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Platform Fee:</strong> <span id="modal-fee"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Total Amount Paid:</strong> <span id="modal-total-paid"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <button type="button" class="btn btn-success btn-sm status-btn" data-status="completed">
                                <i class="fas fa-check"></i> Mark Completed
                            </button>
                            <button type="button" class="btn btn-warning btn-sm status-btn" data-status="cancelled">
                                <i class="fas fa-times"></i> Mark Cancelled
                            </button>
                            <button type="button" class="btn btn-danger btn-sm status-btn" data-status="refunded">
                                <i class="fas fa-undo"></i> Mark Refunded
                            </button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
            </div>

            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- DataTables CSS -->
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
            <!-- Date Range Picker CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

            <!-- DataTables JS -->
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

            <!-- Moment.js (MUST be before daterangepicker) -->
            <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <!-- Date Range Picker JS -->
            <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Initialize DataTable
                    let table = new DataTable('.table', {
                        dom: 'Bfrtip',
                        pageLength: 25,
                        buttons: [
                            {
                                extend: 'csv',
                                text: 'Export CSV',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true; // export all if none checked
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)' // Exclude checkbox and action columns
                                }
                            },
                            {
                                extend: 'excel',
                                text: 'Export Excel',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            },
                            {
                                extend: 'pdf',
                                text: 'Export PDF',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            },
                            {
                                extend: 'print',
                                text: 'Print',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            }
                        ]
                    });

                    // Website filter
                    $('#websiteFilter').on('change', function() {
                        table.column(10).search(this.value).draw();
                    });

                    // Type filter
                    $('#typeFilter').on('change', function() {
                        table.column(11).search(this.value).draw();
                    });



                    // Checklist: Select all
                    $('#selectAll').on('change', function() {
                        $('.row-check').prop('checked', this.checked);
                    });

                    function updateAmountTotal() {
                        let total = 0;
                        table.rows({ search: 'applied' }).every(function () {
                            let data = this.data();
                            let amountCell = data[5]; // Column 6: Amount Gross (0-indexed, so index 5)
                            // Remove HTML tags if present
                            let tempDiv = document.createElement('div');
                            tempDiv.innerHTML = amountCell;
                            let text = tempDiv.textContent || tempDiv.innerText || "";
                            // Remove $ and commas, parse as float
                            let amount = parseFloat(text.replace(/[^0-9.-]+/g,"")) || 0;
                            total += amount;
                        });
                        $('#amount-total').html('$' + total.toLocaleString(undefined, {minimumFractionDigits: 2}));
                    }

                    table.on('draw', updateAmountTotal);
                    updateAmountTotal();
                });
                </script>

                <script>
                let currentTransactionData = {};
                
                $(document).on('click', '.view-btn', function() {
                    const $btn = $(this);
                    currentTransactionData = $btn.data();
                    
                    // Basic transaction details
                    $('#modal-transaction').text($btn.data('transaction') || 'N/A');
                    $('#modal-ip-address').text($btn.data('ip-address') || 'N/A');
                    $('#modal-first-name').text($btn.data('first-name') || 'N/A');
                    $('#modal-last-name').text($btn.data('last-name') || 'N/A');
                    $('#modal-email').text($btn.data('email') || 'N/A');
                    $('#modal-phone').text($btn.data('phone') || 'N/A');
                    $('#modal-type').text($btn.data('type') || 'N/A');
                    $('#modal-status').text($btn.data('status') || 'N/A');
                    $('#modal-website').text($btn.data('website') || 'N/A');
                    $('#modal-date').text($btn.data('date') || 'N/A');
                    
                    // Payment information
                    $('#modal-payment-first-name').text($btn.data('payment-first-name') || 'N/A');
                    $('#modal-payment-last-name').text($btn.data('payment-last-name') || 'N/A');
                    $('#modal-payment-phone').text($btn.data('payment-phone') || 'N/A');
                    $('#modal-payment-email').text($btn.data('payment-email') || 'N/A');
                    $('#modal-payment-address').text($btn.data('payment-address') || 'N/A');
                    $('#modal-payment-city').text($btn.data('payment-city') || 'N/A');
                    $('#modal-payment-state').text($btn.data('payment-state') || 'N/A');
                    $('#modal-payment-country').text($btn.data('payment-country') || 'N/A');
                    $('#modal-payment-zip').text($btn.data('payment-zip') || 'N/A');
                    
                    // Financial details
                    $('#modal-gross').text($btn.data('gross') || '$0.00');
                    $('#modal-fee').text($btn.data('fee') || '$0.00');
                    $('#modal-total-amount').text($btn.data('total-amount') || '$0.00');
                    $('#modal-total-due').text($btn.data('total-due') || '$0.00');
                    $('#modal-total-paid').text($btn.data('total-paid') || '$0.00');
                    
                    // Show/hide investment section - always show payment info, only show investment details for investment type
                    if ($btn.data('type') === 'investment') {
                        $('#investment-section').show();
                        $('#modal-investor-name').text($btn.data('investor-name') || 'N/A');
                        $('#modal-investor-email').text($btn.data('investor-email') || 'N/A');
                        $('#modal-investor-phone').text($btn.data('investor-phone') || 'N/A');
                        $('#modal-investor-type').text($btn.data('investor-type') || 'N/A');
                        $('#modal-share-quantity').text($btn.data('share-quantity') || 'N/A');
                        $('#modal-investment-amount').text($btn.data('investment-amount') || '$0.00');
                        $('#modal-investment-notes').text($btn.data('investment-notes') || 'N/A');
                        
                        // Parse and display investor data
                        try {
                            let investorData = $btn.data('investor-data');
                            if (typeof investorData === 'string') {
                                investorData = JSON.parse(investorData);
                            }
                            if (investorData && typeof investorData === 'object') {
                                let dataHtml = '<div class="border p-2 rounded bg-light">';
                                Object.keys(investorData).forEach(key => {
                                    dataHtml += `<div><strong>${key}:</strong> ${investorData[key]}</div>`;
                                });
                                dataHtml += '</div>';
                                $('#modal-investor-data').html(dataHtml);
                            } else {
                                $('#modal-investor-data').text('No additional data available');
                            }
                        } catch (e) {
                            $('#modal-investor-data').text('Invalid data format');
                        }
                    } else {
                        $('#investment-section').hide();
                    }
                });
                
                // PDF Download functionality
                $('#downloadPdfBtn').on('click', function() {
                    const transactionId = $('#modal-transaction').text();
                    if (transactionId && transactionId !== 'N/A') {
                        window.open(`/admins/transactions/${transactionId}/download-invoice`, '_blank');
                    } else {
                        alert('Transaction ID not found');
                    }
                });

                // Resend Invoice functionality
                $('#resendInvoiceBtn').on('click', function() {
                    const transactionId = $('#modal-transaction').text();
                    const email = $('#modal-email').text();
                    
                    if (transactionId && transactionId !== 'N/A') {
                        if (confirm(`Are you sure you want to resend the invoice to ${email}?`)) {
                            $.ajax({
                                url: `/admins/transactions/${transactionId}/resend-invoice`,
                                method: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    alert('Invoice email sent successfully!');
                                },
                                error: function(xhr) {
                                    alert('Error sending invoice email: ' + (xhr.responseJSON?.message || xhr.responseText));
                                }
                            });
                        }
                    } else {
                        alert('Transaction ID not found');
                    }
                });

                // Legacy PDF generation (kept as fallback)
                $('#downloadLegacyPdfBtn').on('click', function() {
                    // Create comprehensive PDF content
                    const docDefinition = {
                        content: [
                            {
                                text: 'Transaction Details Report',
                                style: 'header',
                                alignment: 'center',
                                margin: [0, 0, 0, 20]
                            },
                            {
                                text: `Generated on: ${new Date().toLocaleDateString()}`,
                                alignment: 'right',
                                margin: [0, 0, 0, 20]
                            },
                            {
                                text: 'Transaction Details',
                                style: 'subheader',
                                margin: [0, 0, 0, 10]
                            },
                            {
                                table: {
                                    headerRows: 1,
                                    widths: ['30%', '70%'],
                                    body: [
                                        ['Field', 'Value'],
                                        ['Transaction ID', $('#modal-transaction').text()],
                                        ['IP Address', $('#modal-ip-address').text()],
                                        ['First Name', $('#modal-first-name').text()],
                                        ['Last Name', $('#modal-last-name').text()],
                                        ['Email', $('#modal-email').text()],
                                        ['Phone', $('#modal-phone').text()],
                                        ['Type', $('#modal-type').text()],
                                        ['Status', $('#modal-status').text()],
                                        ['Website', $('#modal-website').text()],
                                        ['Date', $('#modal-date').text()]
                                    ]
                                }
                            },
                            {
                                text: 'Payment Information',
                                style: 'subheader',
                                margin: [0, 20, 0, 10]
                            },
                            {
                                table: {
                                    headerRows: 1,
                                    widths: ['30%', '70%'],
                                    body: [
                                        ['Field', 'Value'],
                                        ['Payment First Name', $('#modal-payment-first-name').text()],
                                        ['Payment Last Name', $('#modal-payment-last-name').text()],
                                        ['Payment Phone', $('#modal-payment-phone').text()],
                                        ['Payment Email', $('#modal-payment-email').text()],
                                        ['Payment Address', $('#modal-payment-address').text()],
                                        ['Payment City', $('#modal-payment-city').text()],
                                        ['Payment State', $('#modal-payment-state').text()],
                                        ['Payment Country', $('#modal-payment-country').text()],
                                        ['Payment Zip Code', $('#modal-payment-zip').text()]
                                    ]
                                }
                            },
                            {
                                text: 'Financial Details',
                                style: 'subheader',
                                margin: [0, 20, 0, 10]
                            },
                            {
                                table: {
                                    headerRows: 1,
                                    widths: ['30%', '70%'],
                                    body: [
                                        ['Field', 'Value'],
                                        ['Gross Amount', $('#modal-gross').text()],
                                        ['Platform Fee', $('#modal-fee').text()],
                                        ['Total Amount', $('#modal-total-amount').text()],
                                        ['Total Paid', $('#modal-total-paid').text()],
                                        ['Total Due', $('#modal-total-due').text()]
                                    ]
                                }
                            }
                        ],
                        styles: {
                            header: {
                                fontSize: 18,
                                bold: true
                            },
                            subheader: {
                                fontSize: 14,
                                bold: true,
                                color: '#333'
                            }
                        }
                    };
                    
                    // Add investment details if applicable
                    if ($('#investment-section').is(':visible')) {
                        docDefinition.content.push(
                            {
                                text: 'Investment Details',
                                style: 'subheader',
                                margin: [0, 20, 0, 10]
                            },
                            {
                                table: {
                                    headerRows: 1,
                                    widths: ['30%', '70%'],
                                    body: [
                                        ['Field', 'Value'],
                                        ['Investor Name', $('#modal-investor-name').text()],
                                        ['Investor Email', $('#modal-investor-email').text()],
                                        ['Investor Phone', $('#modal-investor-phone').text()],
                                        ['Investor Type', $('#modal-investor-type').text()],
                                        ['Share Quantity', $('#modal-share-quantity').text()],
                                        ['Investment Amount', $('#modal-investment-amount').text()],
                                        ['Investment Notes', $('#modal-investment-notes').text()]
                                    ]
                                }
                            }
                        );
                        
                        // Add investor data dynamically
                        try {
                            let investorDataText = $('#modal-investor-data').text();
                            if (investorDataText && investorDataText !== 'No additional data available' && investorDataText !== 'Invalid data format') {
                                let investorData = currentTransactionData['investor-data'];
                                if (typeof investorData === 'string') {
                                    investorData = JSON.parse(investorData);
                                }
                                if (investorData && typeof investorData === 'object') {
                                    let investorDataBody = [['Field', 'Value']];
                                    Object.keys(investorData).forEach(key => {
                                        investorDataBody.push([key, investorData[key]]);
                                    });
                                    
                                    docDefinition.content.push(
                                        {
                                            text: 'Additional Investor Data',
                                            style: 'subheader',
                                            margin: [0, 20, 0, 10]
                                        },
                                        {
                                            table: {
                                                headerRows: 1,
                                                widths: ['30%', '70%'],
                                                body: investorDataBody
                                            }
                                        }
                                    );
                                }
                            }
                        } catch (e) {
                            console.log('Error processing investor data for PDF:', e);
                        }
                    }
                    
                    pdfMake.createPdf(docDefinition).download(`transaction-${$('#modal-transaction').text()}.pdf`);
                });
                
                // Status change functionality
                $('.status-btn').on('click', function() {
                    const newStatus = $(this).data('status');
                    const transactionId = $('#modal-transaction').text();
                    
                    if (confirm(`Are you sure you want to mark this transaction as ${newStatus}?`)) {
                        $.ajax({
                            url: '/admin/transactions/update-status',
                            method: 'POST',
                            data: {
                                transaction_id: transactionId,
                                status: newStatus,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                alert('Status updated successfully');
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('Error updating status: ' + xhr.responseText);
                            }
                        });
                    }
                });
                </script>
        @endsection
