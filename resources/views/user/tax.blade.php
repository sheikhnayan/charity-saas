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
                        <h5>1099-K Tax</h5>
                    </div>
                    <form action="/users/tax/store" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $data->id ?? null }}">

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Name (as shown on your income tax returns)<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="name" value="{{ $data->name ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Business name (if any) not required
                        </label>
                        <input type="text" class="form-control" id="last_name" name="business_name" value="{{ $data->business_name ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Address<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="address" value="{{ $data->address ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            ZIP / postal code<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="zip" value="{{ $data->zip ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            City<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="city" value="{{ $data->city ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            State<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="state" value="{{ $data->state ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            TIN (Taxpayers Identification Number)<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="tin" value="{{ $data->tin ?? null}}" required>
                    </div>

                    <div class="col-12" style="order: -1;">
                        <label for="last_name" class="form-label required">
                            Type of Tin<span class="text-danger">*</span>
                        </label>
                        <select name="type_of_tin" class="form-control">
                            <option {{ $data->type_of_tin == 'ein' ? 'selected' : '' }} value="ein">EIN</option>
                            <option {{ $data->type_of_tin == 'ssn' ? 'selected' : '' }} value="ssn">SSN</option>
                            <option {{ $data->type_of_tin == 'itin' ? 'selected' : '' }} value="itin">ITIN</option>
                            <option {{ $data->type_of_tin == 'atin' ? 'selected' : '' }} value="atin">ATIN</option>
                        </select>
                    </div>    
                    
                    <div class="col-xxl-12 mb-6 order-0 mt-6">
                        <div class="card p-4" style="background-color: #c2bfbf">
                            Under penalties of perjury, I certify that:
                            <br>
                            <br>
                            1.	The number shown on this form is my correct taxpayer identification number (or I am waiting for a number to be issued to me); and
                        <br>
                            <br>
                            2.	I am not subject to backup withholding because: (a) I am exempt from backup withholding, or (b) I have not been notified by the Internal Revenue Service (IRS) that I am subject to backup withholding as a result of a failure to report all interest or dividends, or (c) the IRS has notified me that I am no longer subject t o backup withholding; and
                            <br>
                            <br>
                            3.	I am a U.S. citizen or other U.S. person (defined below); and
                            <br>
                            <br>
                            4.	The FATCA code(s) entered on this form (if any) indicating that I am exempt from FATCA reporting is correct.
                        </div>
                    </div>


                    <div class="col-md-12 mt-4 mb-4">
                        <input type="checkbox" name="agree" class="" id="agree" required>
                        <label for="agree">I have read, understand, and agree to the above statements.</label>
                    </div>

        
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
@endsection
