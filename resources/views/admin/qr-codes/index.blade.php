@extends('admin.main')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    .qr-generator-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 30px;
        margin-bottom: 25px;
    }
    
    .qr-preview {
        text-align: center;
        padding: 30px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-top: 20px;
    }
    
    .qr-preview img {
        max-width: 400px;
        height: auto;
        border: 3px solid #28a745;
        border-radius: 10px;
        padding: 15px;
        background: white;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
    }
    
    .stats-number {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stats-label {
        font-size: 14px;
        opacity: 0.9;
    }
    
    .qr-action-btn {
        margin: 5px;
    }
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-qrcode me-2"></i> QR Code Generator
                </h4>
                <p class="text-muted mb-0">Create QR codes for donation campaigns and events</p>
            </div>
            @if(isset($websites))
                <div style="min-width: 300px;">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-globe me-1"></i> Switch Website (Admin)
                    </label>
                    <div class="input-group">
                        <select class="form-select" id="websiteSwitcher">
                            @foreach($websites as $w)
                                <option value="{{ $w->id }}" {{ isset($website) && $website->id === $w->id ? 'selected' : '' }}>
                                    {{ $w->name }} ({{ $w->domain }})
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="button" id="switchWebsiteBtn">
                            <i class="fas fa-arrow-right me-1"></i> Switch
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Statistics Row -->
        <div class="row mb-4" id="statsRow">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number" id="totalScans">0</div>
                    <div class="stats-label">
                        <i class="fas fa-mobile-alt me-1"></i> Total QR Scans
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="stats-number" id="totalDonations">$0</div>
                    <div class="stats-label">
                        <i class="fas fa-dollar-sign me-1"></i> Total Raised
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="stats-number" id="completedDonations">0</div>
                    <div class="stats-label">
                        <i class="fas fa-check-circle me-1"></i> Completed
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="stats-number" id="avgDonation">$0</div>
                    <div class="stats-label">
                        <i class="fas fa-chart-line me-1"></i> Average Donation
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Generator Form -->
        <div class="row">
            <div class="col-md-6">
                <div class="qr-generator-card">
                    <h5 class="mb-4">
                        <i class="fas fa-magic me-2 text-primary"></i> Generate QR Code
                    </h5>
                    
                    <form id="qrGeneratorForm">
                        @csrf
                        @if(!isset($websites))
                            <div class="mb-2">
                                <div class="alert alert-info py-2 mb-0">
                                    <i class="fas fa-globe me-1"></i>
                                    Website: <strong>{{ $website->name ?? 'Current' }}</strong> ({{ $website->domain ?? request()->getHost() }})
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-globe me-1"></i> Website *
                                </label>
                                <select class="form-select" name="website_id" id="websiteSelect" required>
                                    @foreach($websites as $w)
                                        <option value="{{ $w->id }}" {{ isset($website) && $website->id === $w->id ? 'selected' : '' }}>{{ $w->name }} ({{ $w->domain }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Admin: select website to generate for</small>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-layer-group me-1"></i> Type *
                            </label>
                            <select class="form-select" name="type" id="typeSelect" required>
                                <option value="donation" selected>Donation</option>
                                {{-- <option value="auction">Auction</option>
                                <option value="ticket">Ticket</option> --}}
                            </select>
                        </div>

                        <div class="mb-3" id="referenceGroup" style="display:none;">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-list me-1"></i> Select Student
                            </label>
                            <select class="form-select" name="reference_id" id="referenceSelect"></select>
                            <small class="text-muted" id="referenceHelp"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-sliders-h me-1"></i> QR Code Size
                            </label>
                            <select class="form-select" name="size" id="qrSize">
                                <option value="300">Small (300px) - For screens</option>
                                <option value="500" selected>Medium (500px) - Recommended</option>
                                <option value="800">Large (800px) - For printing</option>
                                <option value="1000">Extra Large (1000px) - Posters</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-qrcode me-2"></i> Generate QR Code
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- QR Preview -->
            <div class="col-md-6">
                <div class="qr-generator-card">
                    <h5 class="mb-4">
                        <i class="fas fa-eye me-2 text-success"></i> QR Code Preview
                    </h5>
                    
                    <div id="qrPreview" style="display: none;">
                        <div class="qr-preview">
                            <img id="qrCodeImage" src="" alt="QR Code">
                            
                            <div class="mt-3">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    QR Code generated successfully!
                                </div>
                                
                                <div class="text-start mb-3">
                                    <strong>Website:</strong> <span id="previewWebsite">-</span><br>
                                    <strong>Type:</strong> <span id="previewType">-</span><br>
                                    <strong>Item:</strong> <span id="previewItem">-</span><br>
                                    <strong>URL:</strong> <small><code id="previewUrl">-</code></small>
                                </div>
                                
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-success qr-action-btn" onclick="downloadQR()">
                                        <i class="fas fa-download me-1"></i> Download PNG
                                    </button>
                                    <button type="button" class="btn btn-info qr-action-btn" onclick="copyUrl()">
                                        <i class="fas fa-copy me-1"></i> Copy URL
                                    </button>
                                    <button type="button" class="btn btn-secondary qr-action-btn" onclick="printQR()">
                                        <i class="fas fa-print me-1"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="qrPlaceholder" class="text-center py-5">
                        <i class="fas fa-qrcode" style="font-size: 80px; color: #e0e0e0;"></i>
                        <p class="text-muted mt-3">Generate a QR code to see preview</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentQRUrl = '';
let currentWebsiteId = {{ $website->id ?? 'null' }};
const auctions = @json($auctions ?? []);
const tickets = @json($tickets ?? []);
const students = @json($students ?? []);

function populateReference(type) {
    const group = document.getElementById('referenceGroup');
    const select = document.getElementById('referenceSelect');
    const help = document.getElementById('referenceHelp');
    select.innerHTML = '';

    let items = [];
    if (type === 'auction') {
        items = auctions.map(a => ({ id: a.id, label: `${a.title} (${(a.value ?? 0)})` }));
        help.textContent = 'Select an auction item (optional).';
    } else if (type === 'ticket') {
        items = tickets.map(t => ({ id: t.id, label: `${t.name} (${(t.price ?? 0)})` }));
        help.textContent = 'Select a ticket (optional).';
    } else if (type === 'donation') {
        items = students.map(s => ({ id: s.id, label: `${s.name ?? ''} ${s.last_name ?? ''}`.trim() }));
        help.textContent = 'Select a student (optional).';
    }

    // Optional selection
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = '— None —';
    select.appendChild(placeholder);

    items.forEach(it => {
        const opt = document.createElement('option');
        opt.value = it.id;
        opt.textContent = it.label;
        select.appendChild(opt);
    });

    group.style.display = 'block';
}

// Load statistics and initialize form on page load
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('typeSelect');
    const websiteSelectEl = document.getElementById('websiteSelect');

    // Initial populate
    populateReference(typeSelect.value);

    typeSelect.addEventListener('change', function() {
        populateReference(this.value);
    });

    if (websiteSelectEl) {
        // Super admin: load stats for selected website and update on change
        if (websiteSelectEl.value) {
            currentWebsiteId = websiteSelectEl.value;
            loadStatistics(currentWebsiteId);
        }
        websiteSelectEl.addEventListener('change', function() {
            currentWebsiteId = this.value;
            loadStatistics(currentWebsiteId);
        });
    } else {
        if (currentWebsiteId) {
            loadStatistics(currentWebsiteId);
        }
    }
});

// Generate QR Code
document.getElementById('qrGeneratorForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('{{ route('admin.qr.generate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON response:', text);
            throw new Error('Server returned non-JSON response.');
        }

        const result = await response.json();

        if (response.status === 422 && result.errors) {
            const errorMessages = Object.values(result.errors).flat().join(', ');
            showNotification('Validation Error: ' + errorMessages, 'danger');
            return;
        }

        if (result.success) {
            document.getElementById('qrPlaceholder').style.display = 'none';
            document.getElementById('qrPreview').style.display = 'block';
            document.getElementById('qrCodeImage').src = result.qr_code_base64;
            document.getElementById('previewWebsite').textContent = result.website;

            const type = document.getElementById('typeSelect').value;
            const refSel = document.getElementById('referenceSelect');
            const itemText = refSel.options[refSel.selectedIndex]?.text || '—';
            document.getElementById('previewType').textContent = type;
            document.getElementById('previewItem').textContent = itemText;
            document.getElementById('previewUrl').textContent = result.donation_url;

            currentQRUrl = result.donation_url;
            showNotification('QR Code generated successfully!', 'success');
        } else {
            showNotification('Error: ' + (result.message || result.error || 'Unknown error'), 'danger');
        }
    } catch (error) {
        console.error('Full error:', error);
        showNotification('Error generating QR code: ' + error.message, 'danger');
    }
});

// Load Statistics
async function loadStatistics(websiteId) {
    try {
        const response = await fetch(`{{ route('admin.qr.statistics') }}?website_id=${websiteId}`);
        const result = await response.json();
        
        if (result.success) {
            const stats = result.statistics;
            document.getElementById('totalScans').textContent = stats.total_scans;
            document.getElementById('totalDonations').textContent = '$' + (stats.total_amount || 0).toFixed(2);
            document.getElementById('completedDonations').textContent = stats.completed_donations;
            document.getElementById('avgDonation').textContent = '$' + (stats.average_donation || 0).toFixed(2);
        }
    } catch (error) {
        console.error('Error loading statistics:', error);
    }
}

// Download QR Code
function downloadQR() {
    const link = document.createElement('a');
    link.download = 'qr-donation-' + Date.now() + '.png';
    link.href = document.getElementById('qrCodeImage').src;
    link.click();
    showNotification('QR Code downloaded!', 'success');
}

// Copy URL
function copyUrl() {
    navigator.clipboard.writeText(currentQRUrl).then(() => {
        showNotification('URL copied to clipboard!', 'success');
    });
}

// Print QR Code
function printQR() {
    const printWindow = window.open('', '_blank');
    const qrImage = document.getElementById('qrCodeImage').src;
    const type = document.getElementById('previewType').textContent;
    const item = document.getElementById('previewItem').textContent;
    const website = document.getElementById('previewWebsite').textContent;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>QR Code - ${type}</title>
            <style>
                body { 
                    text-align: center; 
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
                img { 
                    max-width: 600px; 
                    border: 3px solid #28a745;
                    padding: 20px;
                    border-radius: 10px;
                }
                h2 { color: #333; }
                p { color: #666; }
            </style>
        </head>
        <body>
            <h2>${website}</h2>
            <h3>${type}${item && item !== '—' ? ' · ' + item : ''}</h3>
            <img src="${qrImage}" alt="QR Code">
            <p style="margin-top: 20px;">Scan to donate</p>
            <p><small>${currentQRUrl}</small></p>
        </body>
        </html>
    `);
    printWindow.document.close();
    setTimeout(() => printWindow.print(), 250);
}

// Show Notification
function showNotification(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => alertDiv.remove(), 3000);
}

// Website Switcher for Admin
document.addEventListener('DOMContentLoaded', function() {
    const switchBtn = document.getElementById('switchWebsiteBtn');
    const websiteSwitcher = document.getElementById('websiteSwitcher');
    
    if (switchBtn && websiteSwitcher) {
        switchBtn.addEventListener('click', function() {
            const websiteId = websiteSwitcher.value;
            if (websiteId) {
                // Reload page with website_id parameter
                window.location.href = `/qr-codes?website_id=${websiteId}`;
            }
        });
        
        // Also allow switching on Enter key
        websiteSwitcher.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                switchBtn.click();
            }
        });
    }
});
</script>
@endsection
