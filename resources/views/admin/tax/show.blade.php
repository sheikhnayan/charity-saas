@extends('admin.main')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="card p-4">
                        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="row gy-3" bis_skin_checked="1">

                                <div class="col-12" style="order: -1;">
                                    <label for="last_name" class="form-label required">
                                        Name (as shown on your income tax returns)
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="name"
                                        value="{{ $data->name ?? null}}" readonly>
                                </div>

                                <div class="col-12" style="order: -1;">
                                    <label for="last_name" class="form-label required">
                                        Business name (if any) not required
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="business_name"
                                        value="{{ $data->business_name ?? null}}" readonly>
                                </div>

                                <div class="col-12" style="order: -1;">
                                    <label for="last_name" class="form-label required">
                                        Address
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="address"
                                        value="{{ $data->address ?? null}}" readonly>
                                </div>

                                <div class="col-12" style="order: -1;">
                                    <label for="last_name" class="form-label required">
                                        ZIP / postal code
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="zip"
                                        value="{{ $data->zip ?? null}}" readonly>
                                </div>

                                <div class="col-12" style="order: -1;">
                                    <label for="last_name" class="form-label required">
                                        City
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="city"
                                        value="{{ $data->city ?? null}}" readonly>
                                </div>

                                <div class="col-12" style="order: -1;">
                                    <label for="last_name" class="form-label required">
                                        State
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="state"
                                        value="{{ $data->state ?? null}}" readonly>
                                </div>

                                <div class="col-12" style="order: -1;">
                                    <label for="last_name" class="form-label required">
                                        TIN (Taxpayers Identification Number)
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="tin"
                                        value="{{ $data->tin ?? null}}" readonly>
                                </div>

                                <div class="col-12" style="order: -1;">
                                    <label for="last_name" class="form-label required">
                                        Type of Tin
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="type_of_tin"
                                        value="{{ $data->type_of_tin ?? null}}" readonly>
                                </div>

                        </form>
                    </div>
                </div>
            </div>

@endsection