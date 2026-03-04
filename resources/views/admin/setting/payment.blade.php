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
                <form action="/admins/payment/update" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12" style="order: -1;">
                    <label for="app_id" class="form-label required">
                        App Key
                    </label>

                    <input type="text" class="form-control" id="app_id" name="app_id" value="{{ $data->app_id ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="transaction_id" class="form-label required">
                        Transaction Key
                    </label>

                    <input type="text" class="form-control" id="transaction_id" name="transaction_id" value="{{ $data->transaction_id ?? null}}">
                </div>

                <div class="col-12" style="order: -1;">
                    <label for="fee" class="form-label required">
                        Fee (%)
                    </label>

                    <input type="text" class="form-control" id="fee" name="fee" value="{{ $data->fee ?? null}}">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                </form>
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

@endsection


