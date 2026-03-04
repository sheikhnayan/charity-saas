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
                        Donate to a Student Runner
                    </h2>
                    <div class="w-xl-75 lead break-all" style="width: 100%; margin-top: 3rem !important; margin: auto; line-height: 10px;">
                        <p style="text-align: center; font-weight: 400;">To donate to a student, search for a name below and click to donate.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-11 col-lg-9 col-xl-7 d-flex align-items-center">
                <div class="input-group input-group-lg">
                    <span class="input-group-text">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                </div>
            </div>
            <div class="col-12 mt-4">
                <table id="studentTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->chunk(2) as $item)

                        <tr>
                            @foreach ($item as $student)
                            <td>
                                <div class="col-lg-12" style="font-size: 12px;">
                                    <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                                        style="width: 100%; max-width: 580px; margin-inline: auto;">
                                        <div class="row gy-3 ">
                                            <div class="col-lg-3 d-flex align-items-center">
                                                <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important">
                                                    <img src="{{ asset($student->photo) }}" style="width: 80px; min-width: 80px; height: 80px; min-height: 80px;">
                                                </div>
                                            </div>

                                            <div class="col-lg-9 d-flex flex-column justify-content-center">
                                                <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                                    {{ $student->name }}
                                                </h2>
                                                <span class="opacity-75 text-center text-lg-start mt-2"></span>
                                                <div class="progress mt-3" role="progressbar" aria-valuenow="{{ $student->donations->sum('amount') }}"
                                                    aria-valuemin="0" aria-valuemax="{{ $student->goal }}" data-primary-color="#2e4053"
                                                    data-secondary-color="#b7bcc4" data-duration="5"
                                                    data-goal-reached="true" style="height: 6px">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                                        style="width: 100%">
                                                    </div>
                                                </div>
                                                <span class="fw-semibold d-block text-center mt-2">
                                                    @php
                                                        $to = $student->donations->sum('amount');
                                                    @endphp
                                                    ${{ $to }} <small class="opacity-75 fw-light">of</small> ${{ $student->goal ?? 0}} <small
                                                        class="opacity-75 fw-light">raised</small>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                                            Last updated {{ $student->updated_at->diffForHumans() }}
                                        </span>
                                        <a href="{{ env('APP_URL') }}/student/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}"
                                            class="stretched-link" target="_blank"></a>
                                    </div>
                                </div>
                            </td>
                            @endforeach
                        </tr>

                        @endforeach
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
