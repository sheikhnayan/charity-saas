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

    <main style="margin-top: 8rem;">
        <section class="text- bg- section-border- " id="687439fc-50ac-4357-bd80-dd2aba7010d2" data-section=""
            style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
            <div class="block-container container " id="block-6f02d3f9-84e5-4ede-bbfd-712dac99fa7d" data-block=""
                data-template="f60cc48059a24febb0a7cb603b78845d"
                data-action="https://gmu-events.com/ajax/block/687439fc-50ac-4357-bd80-dd2aba7010d2/6f02d3f9-84e5-4ede-bbfd-712dac99fa7d"
                style="--block-name:''">


                <h2 class="display-5 fw-normal text-center mt-4 mb-4">
                    Contact Us
                </h2>
            </div>
            <div class="block-container container " id="block-2f22201b-ed43-492a-bf05-885ece798de0" data-block=""
                data-template="1ff245a8096911ebadc10242ac120002"
                data-action="https://gmu-events.com/ajax/block/687439fc-50ac-4357-bd80-dd2aba7010d2/2f22201b-ed43-492a-bf05-885ece798de0"
                style="--block-name:''">


                <div class="row align-items-start gy-5">

                    <div class="col-lg">

                        <div class="row align-items-center flex-column gy-3">



                            <div class="col-lg-12">

                                <div class="d-flex flex-column justify-content-center  h-100 break-all text-dark

bg-">


                                    <div class="lead " style="margin-bottom: 4rem; margin-top: 3rem;">
                                        <p style="text-align: center;">
                                            Have questions about the event or this website? Fill out this form and the
                                            organizer(s)
                                            will get back to you.
                                        </p>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>
        <section class="text- bg- section-border- " id="23c0fa9f-1b3e-4ac9-88a8-ac7e0b9ef0d8" data-section=""
            style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
            <div class="block-container container " id="block-c55189a5-30b0-4b5d-93fa-c09ba3ea7ae4" data-block=""
                data-template="38b2386a3ff24269986eb67b1a7316ae"
                data-action="https://gmu-events.com/ajax/block/23c0fa9f-1b3e-4ac9-88a8-ac7e0b9ef0d8/c55189a5-30b0-4b5d-93fa-c09ba3ea7ae4"
                style="--block-name:''">


                <div class="p-4

col-12 col-xl-6 col-lg-7 col-md-9 mx-auto
">

                    <div class="row align-items-center gy-3 gy-md-4">
                        <div class="col-">
                            <div class="row row-cols-1 gy-3">







                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="block-container container " id="block-0b11d839-1966-464e-b7e2-8f30bdd5d69d" data-block=""
                data-template="e7d0b613d125406ea714907d6507c2a9"
                data-action="https://gmu-events.com/ajax/block/23c0fa9f-1b3e-4ac9-88a8-ac7e0b9ef0d8/0b11d839-1966-464e-b7e2-8f30bdd5d69d"
                style="--block-name:''">


                <div class="form-submission">
                    <form method="POST" action="https://gmu-events.com/ajax/contact-organizer">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label fw-semibold">
                                            Your name
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name">
                                    </div>

                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold">
                                            Email address
                                        </label>
                                        <input type="text" class="form-control" id="email" name="email">
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="hear_from_myevent_0b11d839-1966-464e-b7e2-8f30bdd5d69d"
                                                name="hear_from_myevent">
                                            <label class="form-check-label"
                                                for="hear_from_myevent_0b11d839-1966-464e-b7e2-8f30bdd5d69d">Hear from
                                                MyEvent</label>
                                            <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                data-title="Hear from MyEvent"
                                                data-description="In compliance with the new Anti-Spam CASL legislation, we need your permission to continue communicating
with you. Please confirm your interest in hearing from MyEvent."></i>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <label for="message" class="form-label fw-semibold">
                                            Message
                                        </label>
                                        <textarea class="form-control" id="message" name="message" rows="8"></textarea>
                                    </div>

                                    <input type="hidden" name="template" value="e7d0b613d125406ea714907d6507c2a9">

                                    <div class="col-12">
                                        <small class="text-muted">This form is protected by reCAPTCHA and the Google <a
                                                href="https://policies.google.com/privacy" style="color: #2e4053">Privacy Policy</a>
                                            and <a href="https://policies.google.com/terms" style="color: #2e4053">Terms of Service</a>
                                            apply.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mt-md-4">
                            <button type="submit" class="btn btn-primary btn-lg text-white" style="background-color: #2e4053; border-color: #2e4053">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Include DataTables and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

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
