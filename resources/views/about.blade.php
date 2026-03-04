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
        <section class="text- bg- section-border- " id="db915877-1847-4302-bb87-97d35886e633" data-section=""
            style="background-image: url(); --overlay-color: ; --overlay-opacity: 50%; --section-name: '';">
            <div class="block-container container " id="block-cf852e6a-bf79-4e2a-bd4f-6b7d95ba2a37" data-block=""
                data-template="f60cc48059a24febb0a7cb603b78845d"
                data-action="https://gmu-events.com/ajax/block/db915877-1847-4302-bb87-97d35886e633/cf852e6a-bf79-4e2a-bd4f-6b7d95ba2a37"
                style="margin-bottom: 4rem;">


                <h2 class="display-5 fw-normal text-center">
                    About The Run
                </h2>
            </div>
            <div class="block-container container " id="block-8edc017d-2bde-4c38-bb04-6042c10c0cea" data-block=""
                data-template="1ff245a8096911ebadc10242ac120002"
                data-action="https://gmu-events.com/ajax/block/db915877-1847-4302-bb87-97d35886e633/8edc017d-2bde-4c38-bb04-6042c10c0cea"
                style="--block-name:''">


                <div class="row align-items-end gy-5">

                    <div class="col-lg">

                        <div class="row justify-content-center align-items-end gy-3">


                            <div class="col-lg-5">

                                <div class="d-flex justify-content-center align-items-center">
                                    <figure class="figure text-center w-100 m-0">

                                        <a href="http://www.gmuevents.org" target="_blank">

                                            <img src="https://myfunrun.nyc3.digitaloceanspaces.com/assets/site/page/block/10336/10336.9fea64ea-2006-4529-9ebe-c42412447358.png"
                                                class="figure-img mb-0
            rounded-3 text-images-block-image-md"
                                                alt="" style="height: auto; width: 60%;">

                                        </a>


                                    </figure>
                                </div>

                            </div>


                            <div class="col-lg-7">

                                <div
                                    class="d-flex flex-column justify-content-center p-5 h-100 break-all text-white
rounded-4
bg-navy" style="background-color: navy;">


                                    <div class="lead ">
                                        <p>We are thrilled to announce our upcoming Southern Highlands Preparatory PTO Gear
                                            Me Up™ Fun Run &nbsp;-- an exciting event that brings together students,
                                            families, and the community to promote fitness, teamwork, and school spirit!</p>
                                        <p>Thank you so much for your contributions in making this a successful event!</p>
                                    </div>

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
