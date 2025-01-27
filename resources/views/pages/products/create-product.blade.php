@extends('layouts.master')
@section('title')
    @lang('translation.create-product')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Ecommerce
        @endslot
        @slot('title')
            Create Product
        @endslot
    @endcomponent
    <form id="createproduct-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
        autocomplete="off" class="needs-validation">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product-title-input">Product Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Gallery</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="fs-14 mb-1">Product Image</h5>
                            <p class="text-muted">Add Product main Image.</p>
                            <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="position-absolute top-100 start-100 translate-middle">
                                        <label for="product-image-input" class="mb-0" data-bs-toggle="tooltip"
                                            data-bs-placement="right" title="Select Image">
                                            <div class="avatar-xs">
                                                <div
                                                    class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input class="form-control d-none  @error('image') is-invalid @enderror"
                                            value="" id="product-image-input" name="image" type="file"
                                            accept="image/png, image/gif, image/jpeg" onchange="previewImage(event)">
                                    </div>
                                    <div class="avatar-lg">
                                        <div class="avatar-title bg-light rounded">
                                            <img src="" id="product-img" class="avatar-md h-auto" />
                                        </div>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#addproduct-general-info"
                                    role="tab">
                                    Biaya Produk
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="addproduct-general-info" role="tabpanel">

                                <div class="row">
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="stocks-input">Stocks</label>
                                            <input type="number" class="form-control" id="stocks-input"
                                                placeholder="Stocks" value="1" name="stock">
                                            <div class="invalid-feedback">Please Enter a product stocks.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="product-price-input">Harga Beli</label>
                                            <div class="input-group has-validation mb-3">
                                                <span class="input-group-text" id="product-price-addon">Rp</span>
                                                <input type="number" name="buying_price" id="buying_price"
                                                    class="form-control @error('buying_price') is-invalid @enderror"
                                                    placeholder="Enter price" value="{{ old('buying_price') }}">
                                                @error('buying_price')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="product-price-input">Harga Jual</label>
                                            <div class="input-group has-validation mb-3">
                                                <span class="input-group-text" id="product-price-addon">Rp</span>
                                                <input type="number" name="sell_price" id="sell_price"
                                                    placeholder="Enter price"
                                                    class="form-control @error('sell_price') is-invalid @enderror"
                                                    value="{{ old('sell_price') }}">
                                                @error('sell_price')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <!-- end col -->
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">Submit</button>
                </div>
            </div>
            <!-- end col -->

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Category</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category-select" class="form-label">Category</label>
                            <select class="form-select" id="category-select" name="category" >
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="type-select" class="form-label">Type</label>
                            <select class="js-example-basic-single" id="type-select" name="product_category_type_id">
                                <option value="">Select Type</option>
                            </select>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Supplier</h5>
                    </div>
                    <!-- end card body -->
                    <div class="card-body">
                        <div>
                            <label for="store-select" class="form-label">Nama Toko</label>
                            <select class="js-example-basic-single" name="supplier_id">
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Link Product</h5>
                    </div>
                    <div class="card-body">
                        <div class="hstack gap-3 align-items-start">
                            <div class="flex-grow-1">
                                <input type="text" name="link" id="link"
                                    class="form-control @error('link') is-invalid @enderror" placeholder="Enter link"
                                    value="{{ old('link') }}">
                                @error('link')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

            </div>
        </div>
        <!-- end row -->
    </form>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-product-create.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>

    <script>
        function previewImage(event) {
            const input = event.target;
            const img = document.getElementById('product-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    img.src = e.target.result; // Set the image source to the selected file
                }

                reader.readAsDataURL(input.files[0]); // Read the file as a data URL
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const storeSelect = document.getElementById('store-select');
            const categorySelect = document.getElementById('category-select');
            const typeSelect = document.getElementById('type-select');

            // Fetch product types when the category changes
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value; // Get the selected category ID

                // Fetch product types based on the selected category
                // fetch(`/api/product-categories/${categoryId}/types`) // Adjust the URL to your route
                fetch(
                        `http://localhost:8000/api/product_category_types/${categoryId}`
                    ) // Adjust the URL to your route
                    .then(response => response.json())
                    .then(data => {
                        // Clear existing options
                        typeSelect.innerHTML =
                            '<option value="">Select Type</option>'; // Reset to default option

                        // Populate the select with options from the fetched data
                        data.forEach(type => {
                            const option = document.createElement('option');
                            option.value = type.id; // Assuming each type has an 'id' field
                            option.textContent = type
                                .name; // Assuming each type has a 'name' field
                            typeSelect.appendChild(option);
                        });


                    })
                    .catch(error => {
                        console.error('Error fetching product types:', error);
                    });
            });

            // Trigger change event on page load to populate types for the initially selected category
            categorySelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
