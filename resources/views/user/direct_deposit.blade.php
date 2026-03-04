@extends('user.main')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card p-4">
                <div class="card-header">
                    <h5>Contact Information</h5>
                    <p style="font-weight: bold;">$2.50 per payout and takes 5-7 business days</p>
                    <p class="text-danger">All funds are held for 14 days before they are available for withdrawal *</p>
                    <p class="text-danger">Payouts will be sent in USD *</p>
                </div>
                <div class="card-body">
                    <form action="/users/direct_deposit/store" method="POST" enctype="multipart/form-data">
                    @csrf
    
                    <input type="hidden" name="id" value="{{ $data->id ?? null }}">
    
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
                            ZIP / Postal Code<span class="text-danger">*</span>
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
                        <input type="text" class="form-control" id="last_name" name="name_in_bank" value="{{ $data->name_in_bank ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Name of your bank<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="bank_name" value="{{ $data->bank_name ?? null}}" required>
                    </div>
    
                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Account Type<span class="text-danger">*</span>
                        </label>
                        <select name="account_type" class="form-control">
                            <option {{ $data->account_type == 'checking' ? 'selected' : ''}} value="checking">Checking</option>
                            <option {{ $data->account_type == 'saving' ? 'selected' : ''}} value="saving">Saving</option>
                        </select>
                    </div>
    
                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Account Number<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="account_number" value="{{ $data->account_number ?? null}}" required>
                    </div>
    
                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Routing Number<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="routing_number" value="{{ $data->routing_number ?? null}}" required>
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
