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

    <main style="margin-top: 9rem;">
        <section class="text- bg- section-border- " id="87a0d334-09fd-427d-b1fc-22c35c4e6921" data-section=""
            style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
            <div class="block-container container " id="block-b76130cd-172f-477a-b982-85ef4533885c" data-block=""
                data-template="f60cc48059a24febb0a7cb603b78845d"
                data-action="https://gmu-events.com/ajax/block/87a0d334-09fd-427d-b1fc-22c35c4e6921/b76130cd-172f-477a-b982-85ef4533885c"
                style="--block-name:''">


                <h2 class="display-5 fw-normal text-center">
                    Photos
                </h2>
            </div>
        </section>
        <section class="text- bg- section-border- " id="b9c0a3ea-ba9c-490f-88ef-121dcb773832" data-section=""
            style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
            <div class="block-container container " id="block-d90790c8-5960-4e21-bfa5-4d4116e006ed" data-block=""
                data-template="95df74b8c15a11eab3de0242ac130004"
                data-action="https://gmu-events.com/ajax/block/b9c0a3ea-ba9c-490f-88ef-121dcb773832/d90790c8-5960-4e21-bfa5-4d4116e006ed"
                style="margin-top: 4rem;">


                <style>
                    .lg-toolbar>.lg-maximize {
                        display: none !important;
                    }
                </style>

            </div>
            <div class="block-container container " id="block-68b998bb-51ce-4c53-9db1-0da37c89a321" data-block=""
                data-template="1ff245a8096911ebadc10242ac120002"
                data-action="https://gmu-events.com/ajax/block/b9c0a3ea-ba9c-490f-88ef-121dcb773832/68b998bb-51ce-4c53-9db1-0da37c89a321"
                style="--block-name:''">


                <div class="row align-items-center gy-5">

                    <div class="col-lg">

                        <div class="row align-items-center flex-column gy-3">


                            <div class="col-lg-12">

                                <div class="d-flex justify-content-center align-items-center">
                                    <figure class="figure text-center w-100 m-0">


                                        <img src="https://myfunrun.nyc3.digitaloceanspaces.com/assets/site/page/block/10458/10458.d47c7b18-1b9b-4caf-8cc8-a6b836f89741.png"
                                            class="figure-img mb-0
            rounded-3 text-images-block-image-sm"
                                            alt=""
                                            style="height: auto; width: 40%;">



                                    </figure>
                                </div>

                            </div>


                        </div>

                    </div>
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
