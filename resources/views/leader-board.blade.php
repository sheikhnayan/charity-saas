@extends('layouts.main')

@section('content')

<style>
    #studentTable {
        background-color: #fff !important; /* Set the table background to white */
        border: none !important; /* Remove the table border */
    }

    #studentTable th, #studentTable td {
        background-color: #fff !important; /* Set the background of table cells to white */
        border: none !important; /* Remove borders from table cells */
    }

    #studentTable tbody tr {
        background-color: #fff !important; /* Set the background of table rows to white */
    }

    #studentTable_filter {
        display: none;
    }

    #studentTable_length {
        display: none;
    }

    #studentTable thead {
        display: none; /* Hide the table header */
    }
</style>

<main style="margin-top: 8rem">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-4 mb-4">
                <div class="text-section">
                    <h2 class="display-5 fw-normal text-center mb-3">
                        Leaderboard
                    </h2>
                    <div class="w-xl-75 lead break-all" style="width: 100%; margin-top: 3rem !important; margin: auto; line-height: 10px;">
                        <p style="text-align: center; font-weight: 400;">Browse the top fundraisers below.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="col-lg-12" style="font-size: 12px;">
                    <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                        style="width: 100%; max-width: 580px; margin-inline: auto; background: #ebebeb;">
                        <div class="row gy-3 ">
                            <div class="col-lg-3 d-flex align-items-center">
                                <span style="font-size: 1.5rem !important; font-weight: bold; margin-right: 1rem;">1</span>
                                <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important">
                                    <img src="{{ asset('images/logo.png') }}" style="width: 70px; min-width: 70px; height: 70px; min-height: 70px;">
                                </div>
                            </div>

                            <div class="col-lg-7 d-flex flex-column justify-content-center" style="margin-top: 0px !important;">
                                <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                    Sofia Abinader
                                </h2>

                                {{-- <span class="opacity-75 text-center text-lg-start mt-2"></span> --}}

                                <div class="progress" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" data-primary-color="#2e4053"
                                    data-secondary-color="#28a745" data-duration="5"
                                    data-goal-reached="true" style="height: 14px">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                        style="width: 100%; background-color: #28a745 !important;" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                            <i class="fa-solid fa-award fa-2xl fa-fw position-absolute" aria-hidden="true" style="color: #FFDf01; top: 30px; right: 25px; font-size: 2.5rem !important;"></i>
                            <span class="small fw-bold" style="top: 57px; position: relative; left: -20px; right: unset; font-size: 0.74rem; color: #000;">
                                $16,380.00
                            </span>
                        </span>
                        <a href="https://gmu-events.com/student/104184-zarina-abinader"
                            class="stretched-link" target=""></a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <p class="lead text-center mt-3">
                    418 donations have been made to this site
                </p>
            </div>
            <div class="col-12 mt-4">
                <table id="studentTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="col-lg-12" style="font-size: 12px;">
                                    <div class="p-3 rounded text-center position-relative" style="background: #ebebeb">


                                        <h4 class="fw-semibold">
                                            $100.00
                                        </h4>

                                        <small class="d-block opacity-75 mt-2">
                                            <span title="Donor">Industry Auto Collision Center</span>
                                                                    <i class="fa-solid fa-arrow-right-long fa-fw mx-1 text-success" aria-hidden="true"></i>
                                                <span title="Participant">Hunter Morgan</span>
                                                            </small>


                                        <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                            <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                            Mar 02, 2025
                                        </small>

                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="col-lg-12" style="font-size: 12px;">
                                    <div class="p-3 rounded text-center position-relative" style="background: #ebebeb">


                                        <h4 class="fw-semibold">
                                            $100.00
                                        </h4>

                                        <small class="d-block opacity-75 mt-2">
                                            <span title="Donor">Industry Auto Collision Center</span>
                                                                    <i class="fa-solid fa-arrow-right-long fa-fw mx-1 text-success" aria-hidden="true"></i>
                                                <span title="Participant">Hunter Morgan</span>
                                                            </small>


                                        <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                            <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                            Mar 02, 2025
                                        </small>

                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="col-lg-12" style="font-size: 12px;">
                                    <div class="p-3 rounded text-center position-relative" style="background: #ebebeb">


                                        <h4 class="fw-semibold">
                                            $100.00
                                        </h4>

                                        <small class="d-block opacity-75 mt-2">
                                            <span title="Donor">Industry Auto Collision Center</span>
                                                                    <i class="fa-solid fa-arrow-right-long fa-fw mx-1 text-success" aria-hidden="true"></i>
                                                <span title="Participant">Hunter Morgan</span>
                                                            </small>


                                        <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                            <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                            Mar 02, 2025
                                        </small>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
