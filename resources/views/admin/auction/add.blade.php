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
                        <form action="{{ route('admin.auction.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$website->id}}">
                            <div class="row gy-3" bis_skin_checked="1">

                                <div class="col-md-12 col-lg-12" data-step="1" data-title="Header status"
                                    data-intro="To completely remove the header section from your website, change the status to disabled."
                                    bis_skin_checked="1">
                                    <label for="status" class="form-label">
                                        Status
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                        data-title="Header status"
                                        data-description="To completely remove the header section from your website, change the status to disabled."></i>
                                    <select class="form-select" id="status" name="status">
                                        <option value="1">
                                            Enabled
                                        </option>
                                        <option value="0">
                                            Disabled
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Title
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="title" class="form-control">
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label for="description" class="form-label text-capitalize">
                                        Description
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Value
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="number" name="value" class="form-control">
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="title" class="form-label text-capitalize">
                                        Deadline
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="datetime-local" name="deadline" class="form-control">
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="images" class="form-label text-capitalize">
                                        Images
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"></i>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" onchange="previewAuctionImages(event)" required>
                                    <div id="auction-images-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="timezone" class="form-label text-capitalize">
                                        Timezone
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                    data-title="Timezone"
                                    data-description="Select the timezone for this auction."></i>
                                    <select class="form-select" id="timezone" name="timezone" required>
                                        @foreach(timezone_identifiers_list() as $tz)
                                            <option value="{{ $tz }}">{{ $tz }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label for="page_bg_color" class="form-label text-capitalize">
                                        Page Background Color
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                    data-title="Page Background Color"
                                    data-description="Choose a background color for the item detail page. Defaults to white (#ffffff)."></i>
                                    <div class="input-group">
                                        <input type="color" name="page_bg_color" id="page_bg_color" class="form-control form-control-color" value="#ffffff" style="max-width: 80px;">
                                        <input type="text" class="form-control" id="page_bg_color_text" value="#ffffff" readonly>
                                    </div>
                                    <small class="text-muted">This color will be applied to the background of the auction detail page.</small>
                                </div>

                            <div class="sticky-save-button-container mt-4" bis_skin_checked="1">
                                <div class="sticky-save-button-inner" bis_skin_checked="1">
                                    <button class="btn-hover-shine btn-wide btn btn-shadow btn-success btn-lg w-100 "
                                        type="submit" id="">
                                        Save
                                    </button>
                                </div>
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

                // Sync color picker with text display
                const colorInput = document.getElementById('page_bg_color');
                const colorText = document.getElementById('page_bg_color_text');
                if (colorInput && colorText) {
                    colorInput.addEventListener('change', function() {
                        colorText.value = this.value;
                    });
                }
            </script>

            <script>
                function previewAuctionImages(event) {
                    const preview = document.getElementById('auction-images-preview');
                    preview.innerHTML = '';
                    const files = event.target.files;
                    if (!files.length) return;
                    Array.from(files).forEach(file => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.maxWidth = '120px';
                            img.style.maxHeight = '120px';
                            img.style.objectFit = 'cover';
                            img.className = 'rounded border';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            </script>
        @endsection
