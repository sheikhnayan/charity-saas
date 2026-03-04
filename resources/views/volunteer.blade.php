@extends('layouts.main')

@section('content')
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
    </style>

    <main style="margin-top: 8rem">
        <div class="container">
            <main>
                <section class="text- bg- section-border- " id="a62f69b9-8d0f-4213-b070-a977a437c020" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
                    <div class="block-container container " id="block-406491f2-28a9-46fa-be92-5ba2842c8b73" data-block=""
                        data-template="f60cc48059a24febb0a7cb603b78845d"
                        data-action="https://gmu-events.com/ajax/block/a62f69b9-8d0f-4213-b070-a977a437c020/406491f2-28a9-46fa-be92-5ba2842c8b73"
                        style="--block-name:''">


                        <h2 class="display-5 fw-normal text-center">
                            I Just Want to Help!
                        </h2>
                    </div>
                </section>
                <section class="text- bg- section-border- " id="facb2c3e-5c13-4096-90e0-30de8e263ba8" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: 0%; --section-name: '';">
                    <div class="block-container container " id="block-a24d795a-5479-4e64-8111-729e5a6fd2d5" data-block=""
                        data-template="f397b6192371496897c61c21339f90a0"
                        data-action="https://gmu-events.com/ajax/block/facb2c3e-5c13-4096-90e0-30de8e263ba8/a24d795a-5479-4e64-8111-729e5a6fd2d5"
                        style="--block-name:''">


                        <div class="row gy-3 gy-md-5 justify-content-center align-items-center">

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="d-flex justify-content-center align-items-center">

                                    <a class="text-center btn-facebook-share" href="#" role="button"
                                        data-title="The SHPS PTO Fundraiser 2025" data-url="https://gmu-events.com" style="color: #3b5998">
                                        <i class="fab fa-facebook-square fs-4 text-facebook" role="img"
                                            aria-hidden="true" style="font-size: 4rem !important"></i>

                                        <h4 class="text-dark mt-2 mt-md-3 fs-1.5">
                                            Share on Facebook
                                        </h4>
                                    </a>

                                </div>
                            </div>


                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="d-flex justify-content-center align-items-center">

                                    <a class="text-center btn-linkedin-share" href="#" role="button"
                                        data-title="The SHPS PTO Fundraiser 2025" data-url="https://gmu-events.com" style="color: #0077b5">
                                        <i class="fa-brands fa-linkedin fs-4 text-linkedin" role="img"
                                            aria-hidden="true" style="font-size: 4rem !important"></i>

                                        <h4 class="text-dark mt-2 mt-md-3 fs-1.5">
                                            Share on LinkedIn
                                        </h4>
                                    </a>

                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="d-flex justify-content-center align-items-center">

                                    <button class="text-center btn btn-link btn-clipboard" type="button" role="button"
                                        data-clipboard-text="https://gmu-events.com">
                                        <i class="fa-solid fa-copy fs-4 text-primary" role="img" aria-hidden="true" style="font-size: 4rem !important; color: #2e4053 !important;"></i>

                                        <h4 class="text-dark mt-2 mt-md-3 fs-1.5">
                                            Copy to clipboard
                                        </h4>
                                    </button>

                                </div>
                            </div>

                        </div>
                    </div>
                </section>
                <section class="text- bg- section-border- " id="846df7be-1fde-4091-bb42-55a230f1ef3a" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: 0%; --section-name: '';">
                    <div class="block-container container " id="block-7b6a015d-dfe6-42eb-a779-35f3d10171a8" data-block=""
                        data-template="1ff245a8096911ebadc10242ac120002"
                        data-action="https://gmu-events.com/ajax/block/846df7be-1fde-4091-bb42-55a230f1ef3a/7b6a015d-dfe6-42eb-a779-35f3d10171a8"
                        style="--block-name:''">


                        <div class="row align-items-center gy-5 mt-4">

                            <div class="col-lg">

                                <div class="row align-items-center flex-column gy-3">



                                    <div class="col-lg-12">

                                        <div
                                            class="d-flex flex-column justify-content-center  h-100 break-all text-navy
        rounded-4
        bg-">


                                            <div class="lead ">
                                                <p style="color: #000080; font-weight: 400">We need enthusiastic volunteers to help the event run smoothly! From
                                                    cheering on runners to assisting with lap counting and water stations,
                                                    there’s something for everyone!</p>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="block-container container " id="block-9b80b86d-727f-4e32-a19d-64d53961b31a" data-block=""
                        data-template="30a5a4e0033211ebadc10242ac120002"
                        data-action="https://gmu-events.com/ajax/block/846df7be-1fde-4091-bb42-55a230f1ef3a/9b80b86d-727f-4e32-a19d-64d53961b31a"
                        style="--block-name:''">


                        <h3 class="display-6 fw-normal text-center mb-3 mb-md-4">
                            Volunteer
                        </h3>

                        <div class="form-submission">
                            <form method="PUT"
                                action="https://gmu-events.com/ajax/form/1f2afb48-03e5-4dc3-ac3e-b43fd222f7c9">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                                        <div class="row gy-3">
                                            <div class="col-12">
                                                <label for="1968f446-7550-40db-b974-8e9b4415b082"
                                                    class="form-label required">
                                                    First name
                                                </label>

                                                <input type="text" class="form-control"
                                                    id="1968f446-7550-40db-b974-8e9b4415b082"
                                                    name="first_name" value="">

                                            </div>
                                            <div class="col-12">
                                                <label for="44aa8d4b-54c5-4568-a04e-9c2b465c9122"
                                                    class="form-label required">
                                                    Last name
                                                </label>

                                                <input type="text" class="form-control"
                                                    id="44aa8d4b-54c5-4568-a04e-9c2b465c9122"
                                                    name="last_name" value="">

                                            </div>
                                            <div class="col-12">
                                                <label for="8bfb73a3-5b83-4962-88ad-827f11e5997b"
                                                    class="form-label required">
                                                    Email
                                                </label>

                                                <input type="text" class="form-control"
                                                    id="8bfb73a3-5b83-4962-88ad-827f11e5997b"
                                                    name="email" value="">

                                            </div>
                                            <div class="col-12">
                                                <label for="1d3a0c20-d5b9-4987-91e7-83111fdecdd5" class="form-label ">
                                                    Phone Number
                                                </label>

                                                <input type="text" class="form-control"
                                                    id="1d3a0c20-d5b9-4987-91e7-83111fdecdd5"
                                                    name="phone" value="">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center mt-3 mt-md-4">
                                    <button type="submit" class="btn btn-primary btn-lg text-white" style="background: #2e4053 !important; border-color: #2e4053;">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <section class="text- bg- section-border- " id="b2dd141f-e084-45c7-ba93-d8b6158d65af" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
                    <div class="block-container container " id="block-086fc842-f2e9-4d56-af2e-be42317d11e7"
                        data-block="" data-template="7e729e7e3c534cbf918a45b5540afa84"
                        data-action="https://gmu-events.com/ajax/block/b2dd141f-e084-45c7-ba93-d8b6158d65af/086fc842-f2e9-4d56-af2e-be42317d11e7"
                        style="margin-top: 3rem;">


                        <form method="POST" action="https://gmu-events.com/ajax/donation" class="donation-form-block">
                            @csrf
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
                                <div class="card border-primary shadow" style="border-width: 3px; border-color: #2e4053 !important;">
                                    <div class="card-header bg-primary border-primary rounded-0 text-center text-white fs-2"
                                        style="border-width: 3px; border-color: #2e4053 !important; background-color: #2e4053 !important;">
                                        Make a general donation
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="profile_uuid" value="">

                                        <input type="hidden" name="team_uuid" value="">

                                        <div class="row gy-3">
                                            <div
                                                class="col-12 d-flex flex-column justify-content-center align-items-center">
                                                <label
                                                    for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b2"
                                                    class="form-label " style="color: #000; font-weight: bold;">
                                                    Donate To the SHPS PTO
                                                </label>
                                                <div></div>

                                                <div class="d-flex justify-content-center flex-wrap">
                                                    <input type="radio" data-change-amount="1"
                                                        data-name="4e963109-9506-49a8-b609-a0929944c1b2" data-amount="500"
                                                        class="form-check btn-check select-amount"
                                                        name="question_4e963109-9506-49a8-b609-a0929944c1b2"
                                                        id="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63"
                                                        value="4479f3e5-aac8-4044-ac77-7c3192197e63" autocomplete="off">
                                                    <label class="btn btn-outline-primary m-1"
                                                    style="color: #2e4053 !important; border-color: #2e4053 !important;"
                                                        for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63">Donate
                                                        to the PTO</label>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-right-width: 0; border-color: #2e4053 !important;">$</span>
                                                    <input type="number" placeholder="0"
                                                        class="form-control fs-2 fs-lg-4 text-center border-primary"
                                                        style="border-width: 2px; border-color: #2e4053 !important;" name="donation_amount" value="">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-left-width: 0; border-color: #2e4053 !important;">.00</span>
                                                </div>
                                                <input type="hidden" name="amount" value="">
                                                <div class="text-center">
                                                    <small class="form-text text-muted">
                                                        * The minimum donation amount is 8.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-center align-items-center">
                                                <div class="card border-primary shadow p-2" style="border-width: 2px; border-color: #2e4053 !important;">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="pay_fees" name="pay_fees" checked="">
                                                        <label class="form-check-label fw-semibold" for="pay_fees">
                                                            I elect to pay the fees
                                                        </label>
                                                        <i role="button"
                                                            class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                            data-title="I elect to pay the fees"
                                                            data-description="By selecting this option, you elect to pay the credit card and transaction fees for this donation.The fees will be displayed in the next step."></i>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-12">
                                                <label for="first_name" class="form-label fw-semibold required">
                                                    First name
                                                </label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" value="">
                                            </div>

                                            <div class="col-12">
                                                <label for="last_name" class="form-label fw-semibold required">
                                                    Last name
                                                </label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" value="">
                                            </div>


                                            <div class="col-12">
                                                <label for="email" class="form-label fw-semibold required">
                                                    Email address
                                                </label>
                                                <input type="text" class="form-control" id="email" name="email"
                                                    value="">
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="anonymous_donation" name="anonymous_donation">
                                                    <label class="form-check-label fw-semibold" for="anonymous_donation">
                                                        Anonymous
                                                    </label>
                                                    <i role="button"
                                                        class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                        data-title="Anonymous"
                                                        data-description="Selecting this option will hide your name from everyone but the organizer."></i>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="leave_comment" class="form-label fw-semibold text-capitalize">
                                                    comment
                                                </label>
                                                <textarea class="form-control" id="leave_comment" name="leave_comment" rows="6"></textarea>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="hear_from_myevent_086fc842-f2e9-4d56-af2e-be42317d11e7"
                                                        name="hear_from_myevent">
                                                    <label class="form-check-label"
                                                        for="hear_from_myevent_086fc842-f2e9-4d56-af2e-be42317d11e7">Hear
                                                        from MyEvent</label>
                                                    <i role="button"
                                                        class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                        data-title="Hear from MyEvent"
                                                        data-description="In compliance with the new Anti-Spam CASL legislation, we need your permission to continue communicating
with you. Please confirm your interest in hearing from MyEvent."></i>
                                                </div>
                                            </div>



                                            <input type="hidden" name="template"
                                                value="7e729e7e3c534cbf918a45b5540afa84">

                                            <div class="col-12">
                                                <small class="text-muted">This form is protected by reCAPTCHA and the
                                                    Google <a href="https://policies.google.com/privacy">Privacy Policy</a>
                                                    and <a href="https://policies.google.com/terms">Terms of Service</a>
                                                    apply.</small>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer bg-primary border-primary rounded-0 p-0"
                                        style="border-width: 3px; border-color: #2e4053 !important;">
                                        <button type="submit"
                                            class="btn btn-primary btn-lg w-100 h-100 text-white rounded-0 shadow-none" style="background: #2e4053 !important; border-color: #2e4053 !important;">
                                            Donate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </main>

    <!-- Include DataTables and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Payment Funnel Tracking -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/payment-funnel-tracking.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with default search disabled
            const table = $('#studentTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 25
            });

            // Link the custom search input to the DataTable search
            $('#search').on('keyup', function() {
                const value = $(this).val();
                table.search(value).draw();
            });
        });
    </script>
@endsection
