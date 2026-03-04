<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data->title }}</title>
</head>
<body>
<!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
{{-- <link rel="stylesheet" href="{{ asset('product.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
        #studentTable {
            background-color: #fff !important;
            /* Set the table background to white */
            border: none !important;
            /* Remove the table border */
        }

        #studentTable th,
        #studentTable td {
            background-color: #fff !important;
            /* Set the background of table cells to white */
            border: none !important;
            /* Remove borders from table cells */
        }

        #studentTable tbody tr {
            background-color: #fff !important;
            /* Set the background of table rows to white */
        }

        #studentTable_filter {
            display: none;
        }

        #studentTable_length {
            display: none;
        }

        #studentTable thead {
            display: none;
            /* Hide the table header */
        }

        .non-float {
            margin-bottom: -111px;
        }

        p {
            font-size: 1rem;
            line-height: 1.5;
            font-family: AvenirLTPro-Black, sans-serif;
            /* margin-bottom: 1.5rem; */
        }

        .c-node-ap__auction-results {
            margin-right: 36px;
            margin-bottom: 24px;
            display: inline-block;
            background-color: #f8f9fa;
            border-color: #DBDCDD;
            border: 1px solid;
            border-radius: 4px;
            padding: 24px;
            font-size: 1rem;
        }

        .c-node-ap__fundraising-target {
            margin-bottom: 12px;
        }

        .c-node-ap__auction-total-label {
            margin-bottom: 12px;
            font-size: 1.25rem;
            line-height: 1.2;
            font-weight: bold;
            font-family: AvenirLTPro-Black, sans-serif;
            color: #355159
        }

        .c-node-ap__auction-total-amount {
            font-size: 2rem;
            line-height: 1.5;
            color: #d9b730;
            font-weight: bold;
            font-family: AvenirLTPro-Black, sans-serif;
        }

        .c-node-ap__totalizer {
            height: 18px;
            border-radius: 12px;
            --color-ui: #d9b730;
        }

        .c-node-ap__auction-total-component-label {
            color: #6d6e71
        }

        .c-node-ap__auction-total-component-amount {
            font-size: 1rem;
            line-height: 1.2;
            font-weight: bold;
            font-family: AvenirLTPro-Black, sans-serif;
            color: #000
        }

        .footer-socials .nav-item {
        margin-right: 1rem !important;
        }

        .footer-socials .nav-item a i {
            font-size: 1.5rem;
        }

        footer{
            position: relative;
            width: 100%;
            bottom: 0;
            margin-top: 2rem;
        }
    </style>

    <style>
        /*  Google Font  */
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap');

        *{box-sizing:border-box;margin:0;padding:0;font-family:'Open Sans',sans-serif}
        body{line-height:1.5}

        /* ---------- Layout ---------- */
        .card-wrapper{max-width:1100px;margin:0 auto}
        .card{background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.1);overflow:hidden}
        img{width:100%;display:block}

        /* ---------- Gallery ---------- */
        .img-display{overflow:hidden;width:100%}
        .img-showcase{display:flex;width:100%;transition:transform .5s ease-in-out}
        .img-showcase img{min-width:100%;object-fit:contain}
        .img-select{display:flex;gap:.4rem;margin-top:.5rem}
        .img-item img{cursor:pointer;border-radius:6px}
        .img-item img:hover{opacity:.8}

        /* ---------- Product content ---------- */
        .product-content{padding:2rem 1rem}
        .product-title{font-size:2rem;font-weight:700;color:#12263a;text-transform:capitalize;margin:1rem 0;position:relative}
        /* .product-title::after{content:"";position:absolute;left:0;bottom:0;width:80px;height:4px;background:#12263a} */
        .product-price{margin:1rem 0;font-size:1rem;font-weight:700}
        .new-price span{color:#256eff}
        .product-price::before{content:""; background: #e5e5e5; height: 1px; width: 100%; display: block; margin-bottom: 15px;}
        .product-price::after{content:""; background: #e5e5e5; height: 1px; width: 100%; display: block;}
        #ai-timer-3::after{content:""; background: #e5e5e5; height: 1px; width: 100%; display: block; margin-top: 15px;}
        .ai-timer-10::after{content:""; background: #e5e5e5; height: 1px; width: 100%; display: block; margin-top: 15px;}

        .purchase-info{margin:1.5rem 0}
        .purchase-info .btn{border:1.5px solid #ddd;border-radius:25px;padding:.45rem .8rem;margin-bottom:1rem;color:#fff;background:#256eff}
        .purchase-info .btn:hover{opacity:.9;cursor:pointer}

        /* ---------- Bids table ---------- */
        table.views-table th,
        table.views-table td{vertical-align:middle}
        /* Darker backdrop for image preview only */
#previewModal .modal-backdrop.show { background-color: rgba(0,0,0,.8); }


        /* ---------- Responsive ---------- */
        @media (min-width:992px){
            .card{display:grid;grid-template-columns:repeat(2,1fr);grid-gap:1.5rem}
            .card-wrapper{display:flex;align-items:center;justify-content:center}
            .product-imgs{display:flex;flex-direction:column;justify-content:center}
            .product-content{padding-top:0}
        }
        @media (max-width:991px){
            .card-wrapper{padding:1rem}
        }
    </style>
    @php
        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $doamin)->first();
        $groups = \App\Models\User::where('website_id', $check->id)->where('role', 'group_leader')->get();
        $header = \App\Models\Header::where('website_id', $check->id)->first();
        $footer = \App\Models\Footer::where('website_id', $check->id)->first();
        $setting = \App\Models\Setting::where('user_id', $check->user_id)->first();
        $user = \App\Models\User::where('id', $check->user_id)->first();
    @endphp
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif

<div class="card-wrapper" style="margin-top: 8rem;">
    <div class="card p-4">

        {{-- =========== Left column: Images =========== --}}
        <div class="product-imgs">
            <div class="img-display">
                <div class="img-showcase">
                    @foreach ($data->images as $item)
                        <img src="{{ asset('/uploads/'.$item->image) }}" alt="product image">
                    @endforeach
                </div>
            </div>

            <div class="img-select">
                @foreach ($data->images as $key => $item)
                    <div class="img-item">
                        <a href="#" data-id="{{ $key + 1 }}">
                            <img src="{{ asset('/uploads/'.$item->image) }}" alt="thumbnail" width="130" height="130">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- =========== Right column: Details =========== --}}
        <div class="product-content">
            <h4 class="product-title">{{ $data->title }}</h4>

            <div class="product-price">
                <p class="new-price">
                    Current Bid:
                    <span id="auction-price-{{ $data->id }}"
                          data-live-item="price"
                          data-tcid="{{ $data->id }}:price">
                        ${{ $data->starting_price ?? 0 }}
                    </span>
                </p>
            </div>

            {{-- Timer --}}
            <div id="ai-timer-{{ $data->id }}" class="ai-timer-10 mb-3">
                <p class="new-price fw-bold mb-1">Time Remaining:</p>
                <span class="d-inline-flex gap-2">
                    <span><span id="days-{{ $data->id }}">0</span> Days</span>
                    <span><span id="hours-{{ $data->id }}">0</span> Hrs</span>
                    <span><span id="minutes-{{ $data->id }}">0</span> Mins</span>
                    <span><span id="seconds-{{ $data->id }}">0</span> Secs</span>
                </span>
            </div>

            {{-- Description --}}
            <div class="product-detail">
                <h2>About this item:</h2>
                {!! $data->description !!}
            </div>

            {{-- Place Bid button --}}
            <div class="purchase-info">
                <button type="button"
                        class="btn js-button open-modal"
                        data-bs-toggle="modal"
                        data-bs-target="#bidModal">
                    Place Bid
                </button>
            </div>
        </div>

        {{-- =========== Bid History table =========== --}}
        <div class="c-node-ai__additional-wrap w-100 px-4 pb-4">
            <h2 class="h4 mb-3">Bidding</h2>
            <div class="table-responsive">
                <table class="views-table table table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Bidder</th>
                            <th scope="col">Time</th>
                            <th scope="col">Bid</th>
                        </tr>
                    </thead>
                    <tbody id="bid-history-body">
                        <tr><td colspan="3" class="text-center">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- =========== Bid Modal =========== --}}
<div class="modal fade" id="bidModal" tabindex="-1" aria-labelledby="bidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="bidForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bidModalLabel">Place a Bid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="bidderName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="bidderName" required>
                </div>
                <div class="mb-3">
                    <label for="bidderEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="bidderEmail" required>
                </div>
                <div class="mb-3">
                    <label for="bidAmount" class="form-label">Bid Amount</label>
                    <input type="number" class="form-control" id="bidAmount" min="1" required>
                    <div class="invalid-feedback" id="bidAmountError"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit Bid</button>
            </div>
        </form>
    </div>
</div>

<!-- === Image Preview Modal === -->
<div class="modal" id="previewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">   <!-- lg = 900 px wide -->
    <div class="modal-content bg-transparent border-0 shadow-none">
      <img id="previewImg" src="" class="img-fluid rounded" alt="preview">
    </div>
  </div>
</div>

        @if ($footer->status == 1)
<footer class="standard-client-footer text-white bg-primary" data-footer="" style="
background-color: {{ $footer->background }} !important;
">
    <div class="container">

                    <p class="lead text-center pt-4" style="color: {{ $footer->color }} !important">
                {{ $footer->message }}
            </p>
                    @if ($footer->menu == 1)
                        <div class="nav justify-content-center">
                            @foreach ($check->pages->sortBy('position') as $item)

                            @if($item->status == 1)

                            <div class="nav-item">
                                <a class="nav-link active" href="/page/{{ str_replace(' ', '-', strtolower($item->name)) }}" style="color:{{ $footer->color }} !important" aria-current="page">
                                {{ $item->name }}
                                </a>
                            </div>
                            @endif

                            @endforeach
                                                    </div>
                    @endif

                    @if ($footer->social == 1)
                        <ul class="nav justify-content-center footer-socials mt-4 mb-4">
                            @if ($footer->facebook)
                                <li class="nav-item">
                                    <a href="{{ $footer->facebook }}" target="_blank">
                                        <i class="fa-brands fa-facebook fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">facebook</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->instagram)
                                <li class="nav-item">
                                    <a href="{{ $footer->instagram }}" target="_blank">
                                        <i class="fa-brands fa-instagram fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">instagram</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->linkedin)
                                <li class="nav-item">
                                    <a href="{{ $footer->linkedin }}" target="_blank">
                                        <i class="fa-brands fa-linkedin fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">linkedin</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->pinterest)
                                <li class="nav-item">
                                    <a href="{{ $footer->pinterest }}" target="_blank">
                                        <i class="fa-brands fa-pinterest fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">pinterest</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->x)
                                <li class="nav-item">
                                    <a href="{{ $footer->x }}" target="_blank">
                                        <i class="fa-brands fa-x-twitter fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">x</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->youtube)
                                <li class="nav-item">
                                    <a href="{{ $footer->youtube }}" target="_blank">
                                        <i class="fa-brands fa-youtube fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">youtube</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->blue_sky)
                                <li class="nav-item">
                                    <a href="{{ $footer->blue_sky }}" target="_blank">
                                        <i class="fa-solid fa-cloud fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">blue sky</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->tiktok)
                                <li class="nav-item">
                                    <a href="{{ $footer->tiktok }}" target="_blank">
                                        <i class="fa-brands fa-tiktok fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">tiktok</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif

                @if ($footer->copy_right != null)
                    <p class="text-center" style="margin-bottom: 0px;">
                        <small style="color: {{ $footer->color }}">
                            {{ $footer->copy_right }}
                        </small>
                    </p>
                @endif
    </div>
    @if ($footer->privacy == 1)
        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <ul style="display: inline-flex; list-style: none; margin-left: 0px; margin-top: 20px; margin-bottom: 5px;">
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->refund ? $setting->refund_page->name : '#')) }}">Refund Policy</a>
                        </li>
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->privacy ? $setting->privacy_page->name : '#')) }}">Privacy Policy</a>
                        </li>
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->terms ? $setting->terms_page->name : '#')) }}">Terms of service</a>
                        </li>
                    </ul>
            </div>
        </div>
    @endif
</footer>
@endif


<input type="hidden" id="product-id" value="{{ $data->id }}">
<script>
function startAuctionTimer(deadline, id) {
    function update() {
        const now = new Date().getTime();
        const target = new Date(deadline).getTime();
        let timeLeft = target - now;

        if (timeLeft <= 0) {
            document.getElementById('days-' + id).textContent = 0;
            document.getElementById('hours-' + id).textContent = 0;
            document.getElementById('minutes-' + id).textContent = 0;
            document.getElementById('seconds-' + id).textContent = 0;
            return;
        }

        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        document.getElementById('days-' + id).textContent = days;
        document.getElementById('hours-' + id).textContent = hours;
        document.getElementById('minutes-' + id).textContent = minutes;
        document.getElementById('seconds-' + id).textContent = seconds;
    }
    update();
    setInterval(update, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
        startAuctionTimer("{{ $data->dead_line }}", "{{ $data->id }}");
});



</script>

<script>
    $('.open-modal').on('click', function(){
        $('#bidModal').modal('show');
        $('#bidModal').removeClass('fade');
        $('#bidModal').addClass('show');
    })
</script>

<script type="module">
    import { initializeApp, getApps } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
    import { getFirestore, collection, addDoc, query, where, orderBy, getDocs, limit } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js";

    /* ──────────────────────────
       1.  Number‑format helper
       ────────────────────────── */
    const formatMoney = (num, decimals = 0) =>
        Number(num).toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });

    // Firebase config
    const firebaseConfig = {
        apiKey: "AIzaSyD0QsLeSIAFeBBUouzhgUQ3WEGfM1MAYA4",
        authDomain: "charity-390ca.firebaseapp.com",
        projectId: "charity-390ca",
        storageBucket: "charity-390ca.firebasestorage.app",
        messagingSenderId: "875958450032",
        appId: "1:875958450032:web:338aeac86307e5ab3e41b5",
        measurementId: "G-FC73HL5XF3"
    };

    // Initialize Firebase only once
    let app;
    if (!getApps().length) {
        app = initializeApp(firebaseConfig);
    } else {
        app = getApps()[0];
    }
    const firestore = getFirestore(app);

    const auctionId   = "{{ $data->id }}";
    let   lastBid     = Number("{{ $data->starting_price ?? 0 }}");
    const priceDiv    = document.getElementById('auction-price-{{ $data->id }}');
    const bidAmountInput = document.getElementById('bidAmount');

    /* ──────────────────────────
       2.  Show latest bid
       ────────────────────────── */
    async function showLatestBid() {
        // Get latest bid from Firestore only
        const q = query(
            collection(firestore, "bid"),
            where("auction_id", "==", auctionId),
            orderBy("amount", "desc"),
            limit(1)
        );
        const querySnapshot = await getDocs(q);
        if (!querySnapshot.empty) {
            const amount = querySnapshot.docs[0].data().amount;
            lastBid = Number(amount);
            priceDiv.textContent = '$' + formatMoney(lastBid, 2);
            if (bidAmountInput) bidAmountInput.min = lastBid + 1;
        }
    }

    /* ──────────────────────────
       3.  Bid history table
       ────────────────────────── */
    async function loadBidHistory() {
        const q = query(
            collection(firestore, "bid"),
            where("auction_id", "==", auctionId),
            orderBy("timestamp", "desc")
        );
        const querySnapshot = await getDocs(q);

        const tbody = document.getElementById('bid-history-body');
        tbody.innerHTML = '';

        if (querySnapshot.empty) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center">No bids yet.</td></tr>`;
            return;
        }

        querySnapshot.forEach(doc => {
            const bid  = doc.data();
            const date = bid.timestamp && bid.timestamp.toDate
                         ? bid.timestamp.toDate()
                         : new Date(bid.timestamp);

            const formattedDate = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + ' ' +
                                  date.toLocaleDateString([], { day: '2-digit', month: 'short', year: 'numeric' });

            tbody.innerHTML += `
                <tr>
                    <td class="c-views-table__field c-views-table__field--name">${bid.name || ''}</td>
                    <td class="c-views-table__field c-views-table__field--created">${formattedDate}</td>
                    <td class="c-views-table__field c-views-table__field--bid-amount">$${formatMoney(bid.amount, 2)}</td>
                </tr>
            `;
        });
    }

    /* ──────────────────────────
       4.  Page‑load hooks
       ────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        showLatestBid();
        loadBidHistory();
    });

    /* ──────────────────────────
       5.  Bid‑submit logic
       ────────────────────────── */
    document.getElementById('bidForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const name   = document.getElementById('bidderName').value.trim();
        const email  = document.getElementById('bidderEmail').value.trim();
        const amount = Number(document.getElementById('bidAmount').value);

        // Validate amount
        if (isNaN(amount) || amount <= lastBid) {
            const bidInput = document.getElementById('bidAmount');
            bidInput.classList.add('is-invalid');
            document.getElementById('bidAmountError').textContent =
                `Bid must be greater than $${formatMoney(lastBid, 2)}`;
            return;
        } else {
            document.getElementById('bidAmount').classList.remove('is-invalid');
            document.getElementById('bidAmountError').textContent = '';
        }

        try {
            // Save bid
            await addDoc(collection(firestore, "bid"), {
                auction_id: auctionId,
                name,
                email,
                amount,
                timestamp: new Date()
            });

            // Refresh UI
            await showLatestBid();
            await loadBidHistory();

            // Close modal
            const modalEl = document.getElementById('bidModal');
            let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();

            // Redirect to payment
            const id = document.getElementById('product-id').value;
            window.location.href = `/authorize/payment/auction/${id}?amount=${amount}`;

        } catch (error) {
            alert('Error saving bid: ' + error.message);
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    /* grab pieces created by the previous script */
    const showcase  = document.querySelector('.img-showcase');
    const slides    = Array.from(showcase.children);
    let   current   = 0;   // we will keep this in sync

    /* Preview modal setup */
    const previewEl   = document.getElementById('previewModal');
    const previewImg  = document.getElementById('previewImg');
    const previewModal= new bootstrap.Modal(previewEl);

    /* --- existing slideTo() from earlier --- */
    function slideTo(idx){
        const w = slides[0].getBoundingClientRect().width;
        showcase.style.transform = `translateX(-${w * idx}px)`;
        current = idx;
    }

    /* thumbnails */
    document.querySelectorAll('.img-select a').forEach((thumb,i)=>{
        thumb.addEventListener('click',e=>{
            e.preventDefault();
            slideTo(i);
        });
    });

    window.addEventListener('resize',()=>slideTo(current));

    /* BIG IMAGE CLICK -> open preview */
    showcase.addEventListener('click', () => {
        previewImg.src = slides[current].getAttribute('src'); // same image, larger
        previewModal.show();
    });
});
</script>



</body>
</html>
