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
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center;">
                                    <h5>Contact Details</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Name
                                </label>
                                <input type="text" class="form-control" id="last_name" name="name" value="{{ $mailed->name ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Email
                                </label>
                                <input type="email" class="form-control" id="last_name" name="email" value="{{ $mailed->email ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Phone
                                </label>
                                <input type="text" class="form-control" id="last_name" name="phone" value="{{ $mailed->phone ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Address
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address" value="{{ $mailed->address ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    city
                                </label>
                                <input type="text" class="form-control" id="last_name" name="city" value="{{ $mailed->city ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    country
                                </label>
                                <input type="text" class="form-control" id="last_name" name="country" value="{{ $mailed->country ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    state
                                </label>
                                <input type="text" class="form-control" id="last_name" name="state" value="{{ $mailed->state ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    zip
                                </label>
                                <input type="text" class="form-control" id="last_name" name="zip" value="{{ $mailed->zip ?? null}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 mb-6 order-0">
                    <div class="card p-4">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center;">
                                    <h5>Direct Deposit Settings</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Name In Bank
                                </label>
                                <input type="text" class="form-control" id="last_name" name="name_in_bank" value="{{ $direct->name_in_bank ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Account Type
                                </label>
                                <input type="text" class="form-control" id="last_name" name="account_type" value="{{ $direct->account_type ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Account Number
                                </label>
                                <input type="text" class="form-control" id="last_name" name="account_number" value="{{ $direct->account_number ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Routing Number
                                </label>
                                <input type="text" class="form-control" id="last_name" name="routing_number" value="{{ $direct->routing_number ?? null}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 mb-6 order-0">
                    <div class="card p-4">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center;">
                                    <h5>Mailed Check Settings</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Make the check payable to
                                </label>
                                <input type="text" class="form-control" id="last_name" name="paybale_to" value="{{ $mailed->paybale_to ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Person to send the check to
                                </label>
                                <input type="text" class="form-control" id="last_name" name="send_check_to" value="{{ $mailed->send_check_to ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Address to send the check to
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $mailed->address_to_send ?? null}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 mb-6 order-0">
                    <div class="card p-4">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center;">
                                    <h5>Wire Transfer Setting</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Name on bank account
                                </label>
                                <input type="text" class="form-control" id="last_name" name="paybale_to" value="{{ $wire->paybale_to ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Name of your bank
                                </label>
                                <input type="text" class="form-control" id="last_name" name="send_check_to" value="{{ $wire->send_check_to ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Account number
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->send_check_to ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Routing number
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->address_to_send ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Bank address
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->city_to_send ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Zip / postal code
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->zip ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    City
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->city ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Country
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->country ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    State / Province
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->state ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Beneficiary Address
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->beneficiary_address ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Beneficiary Zip / postal code
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->beneficiary_zip ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                     Beneficiary City
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->beneficiary_city ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Beneficiary Country
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->beneficiary_country ?? null}}" readonly>
                            </div>

                            <div class="col-12" style="order: -1;">
                                <label for="last_name" class="form-label required">
                                    Beneficiary State
                                </label>
                                <input type="text" class="form-control" id="last_name" name="address_to_send" value="{{ $wire->beneficiary_state ?? null}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->
            <script>
                ClassicEditor
                    .create(document.querySelector('#description'))
                    .catch(error => {
                        console.error(error);
                    });
            </script>

            <!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('menu-sortable');
        if (el) {
            Sortable.create(el, {
                handle: '.handle',
                animation: 150,
                onEnd: function () {
                    // Update hidden inputs to match new order
                    let ids = [];
                    el.querySelectorAll('li').forEach(function(li, idx) {
                        li.querySelector('input[name="menu_order[]"]').value = li.getAttribute('data-id');
                    });
                }
            });
        }
    });
</script>
        @endsection
