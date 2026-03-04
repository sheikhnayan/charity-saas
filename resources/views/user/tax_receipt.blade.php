@extends('user.main')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <div class="card-">
                    <h5>General information</h5>
                </div>
                <form action="/users/tax-receipt/store" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $data->id ?? null }}">

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Organization
                    </label>
                    <input type="text" class="form-control" id="last_name" name="organization" value="{{ $data->organization ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Phone number
                    </label>
                    <input type="text" class="form-control" id="last_name" name="phone_number" value="{{ $data->phone_number ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        website
                    </label>
                    <input type="text" class="form-control" id="last_name" name="website" value="{{ $data->website ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Charitable ID
                    </label>
                    <input type="text" class="form-control" id="last_name" name="charitable_id" value="{{ $data->charitable_id ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Reference <span class="text-danger">(Reference regarding your organization's tax receipt.)</span>
                    </label>
                    <input type="text" class="form-control" id="last_name" name="reference" value="{{ $data->reference ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Number prefix <span class="text-danger">(The letter before your tax receipt number.)</span>
                    </label>
                    <input type="text" class="form-control" id="last_name" name="number_prefix" value="{{ $data->number_prefix ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Starting number span <span class="text-danger">(The starting number of the first tax receipt.)</span>
                    </label>
                    <input type="text" class="form-control" id="last_name" name="starting_number" value="{{ $data->starting_number ?? null}}">
                </div>
            </div>
            <div class="card p-4 mt-4">
                <div class="card-header" style="padding-left: 0px;">
                    <h5>Address Details</h5>
                </div>
                <div class="row">

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Address <span class="text-danger">*<span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="address" value="{{ $data->address ?? null}}" required>
                    </div>

                    <div class="col-6" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Zip / postal code <span class="text-danger">*<span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="zip" value="{{ $data->zip ?? null}}" required>
                    </div>

                    <div class="col-6" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            City <span class="text-danger">*<span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="address" value="{{ $data->city ?? null}}" required>
                    </div>

                    <div class="col-6" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Country <span class="text-danger">*<span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="country" value="{{ $data->country ?? null}}" required>
                    </div>

                    <div class="col-6" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            State / Province <span class="text-danger">*<span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="state" value="{{ $data->state ?? null}}" required>
                    </div>
                </div>



            </div>
            <div class="card p-4 mt-4">
                <div class="card-header">
                    <h5>Logo & Signature</h5>
                </div>

                <div class="card-body">
                    <div class="col-12" style="order: -1;">
                        <label for="logo" class="form-label required">
                            Logo
                        </label>
                        <input type="file" class="form-control" id="logo" name="logo">

                        @if($data && $data->logo)
                            <img class="mt-4" src="{{ asset('storage/'.$data->logo) }}" width="200px">
                        @endif
                    </div>
                    <div class="col-12" style="order: -1;">
                        <label for="signature" class="form-label required">
                            Signature
                        </label>
                        <input type="file" class="form-control" id="signature" name="signature">

                        @if($data && $data->signature)
                            <img class="mt-4" src="{{ asset('storage/'.$data->signature) }}" width="200px">
                        @endif
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
