@extends('user.main')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <div class="card-header" style="padding-bottom: 0px;">
                    <h5>Contact Information</h5>
                    <p style="font-weight: bold;">$30 per payout, takes 2-5 business days</p>
                    <p class="text-danger">All funds are held for 14 days before they are available for withdrawal *</p>
                    <p class="text-danger">Payouts will be sent in USD *</p>
                </div>
                <form action="/users/wire_transfer/store" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $data->id ?? null }}">
                <div class="card-body">

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Full name<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="name" value="{{ $data->name ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Email<span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="last_name" name="email" value="{{ $data->email ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Phone<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="phone" value="{{ $data->phone ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Address<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="address" value="{{ $data->address ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            City<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="city" value="{{ $data->city ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Country<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="country" value="{{ $data->country ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            State / Province<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="state" value="{{ $data->state ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Zip / postal code<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="zip" value="{{ $data->zip ?? null}}" required>
                    </div>
                </div>

            </div>

            <div class="card p-4 mt-4">
                <div class="card-header">
                    <h5>Banking Information</h5>
                </div>
                <div class="card-body">
                    <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Name on bank account<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="last_name" name="paybale_to" value="{{ $data->paybale_to ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Name of your bank<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="last_name" name="send_check_to" value="{{ $data->send_check_to ?? null}}" required>
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Account number<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $data->address_to_send ?? null}}" required>
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Routing Number<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="last_name" name="city_to_send" value="{{ $data->address_to_send ?? null}}" required>
                </div>
                <div class="col-12" style="order: -1;">
                    <label for="last_name" class="form-label required">
                        Bank address<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $data->address_to_send ?? null}}" required>
                </div>


                </div>
            </div>

            <div class="card p-4 mt-4">
                <div class="card-header">
                    <h5>Beneficiary information</h5>
                </div>
                <div class="card-body">
                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Beneficiary Address<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="beneficiary_address" value="{{ $data->beneficiary_address ?? null}}">
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Beneficiary Zip / postal code<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="beneficiary_zip" value="{{ $data->beneficiary_zip ?? null}}">
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                                Beneficiary City<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="beneficiary_city" value="{{ $data->beneficiary_city ?? null}}">
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Beneficiary Country<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="beneficiary_country" value="{{ $data->beneficiary_country ?? null}}">
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Beneficiary State<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="beneficiary_state" value="{{ $data->beneficiary_state ?? null}}">
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
