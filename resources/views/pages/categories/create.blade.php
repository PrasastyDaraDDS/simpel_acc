@extends('layouts.master')
@section('title')
    Category
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Ecommerce
        @endslot
        @slot('title')
            Create Category
        @endslot
    @endcomponent
    <form id="createproduct-form" action="{{ route('product_categories.store') }}" method="POST" enctype="multipart/form-data"
        autocomplete="off" class="needs-validation">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="product-title-input">Category Name</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">Submit</button>
                </div>
            </div>
            <!-- end col -->

        </div>
        <!-- end row -->
    </form>
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-product-create.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

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
            const categorySelect = document.getElementById('choices-publish-status-input');
            const typeSelect = document.getElementById('choices-type-visibility-input');

            // Initialize Choices.js for the category dropdown
            new Choices(categorySelect, {
                searchEnabled: false,
            });

            // Initialize Choices.js for the type dropdown
            const typeChoices = new Choices(typeSelect, {
                searchEnabled: true, // Enable search for the type dropdown
            });

            // Fetch product types when the category changes
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value; // Get the selected category ID

                // Fetch product types based on the selected category
                // fetch(`/api/product-categories/${categoryId}/types`) // Adjust the URL to your route
                fetch(
                    `http://localhost:8000/api/product_category_types/${categoryId}`) // Adjust the URL to your route
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

                        // Update Choices.js with new options
                        typeChoices.setChoices(data.map(type => ({
                            value: type.id,
                            label: type.name,
                            selected: false,
                            disabled: false
                        })), 'value', 'label', false);
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
