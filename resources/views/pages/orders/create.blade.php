@extends('layouts.master')
@section('title')
    Orders
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
            Create Orders
        @endslot
    @endcomponent

    {{-- if error --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error occurred!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <form id="createproduct-form" action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data"
        autocomplete="off" class="needs-validation">
        @csrf
        <div class="row">
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order</h5>
                    </div>
                    <div class="card-body">


                        <div class="mb-3">
                            <div class="row mb-3">

                                <div class="col-lg-4">
                                    <label for="product-select" class="form-label">Tipe Pemesanan</label>
                                    <div class="">
                                        <div class="form-check ">
                                            <input class="form-check-input" type="radio" name="order_type" id="buy"
                                                value="buy">
                                            <label class="form-check-label" for="buy">Beli dari toko</label>
                                        </div>
                                        <div class="form-check ">
                                            <input class="form-check-input" type="radio" name="order_type" id="sell"
                                                value="sell">
                                            <label class="form-check-label" for="sell">Jual ke customer</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6" id="customer-container" style="display: none">
                                    <label for="customer-select" class="form-label">Customer</label>
                                    <select class="js-example-basic-single" name="participant_id">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-5">
                                    <label for="payment-date-input" class="form-label">Tanggal Pemesanan</label>
                                    <input type="date" class="form-control" name="order_date" id="payment-date-input">
                                </div>
                                <div class="col-lg-3">
                                    <label for="customer-select" class="form-label">Status Pemesanan</label>
                                    <select class="js-example-basic-single" name="order_status_id" id="order-status-select">
                                    </select>

                                </div>
                                <div class="col-lg-4">
                                    <label for="customer-select" class="form-label">Ongkos Kirim</label>
                                    <input type="number" class="form-control" id="shipping_cost-input" name="shipping_cost"
                                        placeholder="Ongkos Kirim">

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Produk</h5>

                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="addproduct-general-info" role="tabpanel">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label for="category-select" class="form-label">Kategori Produk</label>
                                            <select class="form-control" id="category-select" name="category_id">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        data-price="{{ $category->buying_price }}" data-choices>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="type-select" class="form-label">Tipe Produk</label>
                                            <select class="form-control" id="type-select" name="type_id" data-choices>

                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="product-select" class="form-label">Nama Produk</label>
                                            <select class="js-example-basic-single" id="product-select" name="product_id"
                                                data-choices data-choices-search-false>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="quantity-input">Quantity <span
                                                    class="text-muted" id="stock_item"></span></label>
                                            <input type="number" class="form-control" id="quantity-input"
                                                name="quantity" placeholder="quantity">
                                            <div class="invalid-feedback">Please Enter a order quantity.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="buying_price">Harga Total</label>
                                            <div class="input-group has-validation mb-3">
                                                <span class="input-group-text" id="product-price-addon">Rp</span>
                                                <input type="text" readonly name="" id="buying_price"
                                                    class="form-control @error('buying_price') is-invalid @enderror"
                                                    placeholder="Enter price" value="{{ old('buying_price') }}" required>
                                                @error('buying_price')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                                <button type="button" class="btn btn-primary" id="add-product">Tambahkan Produk</button>
                            </div>

                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->
                    </div>
                    <!-- end card body -->
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order</h5>
                    </div>
                    <div class="card-body">

                        <div class="live-preview mb-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Tipe</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Kuantitas</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Total Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-table">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">Submit</button>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>

                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Metode</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-container">
                                    <tr class="payment-entry">
                                        <td>
                                            <input type="date" class="form-control form-control-sm"
                                                name="payment_date[]" required>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" id="payment-select"
                                                name="payment_method_id[]">
                                                @foreach ($methods as $method)
                                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group has-validation input-group-sm">
                                                <span class="input-group-text" id="product-price-addon">Rp</span>
                                                <input type="number" class="form-control" name="amount[]"
                                                    placeholder="Enter amount" required>
                                            </div>
                                        </td>


                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-payment"><i
                                                    class="ri-delete-bin-line"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2">Total</td>
                                        <td colspan="2" id="total-payment-val"></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- end table -->
                        </div>
                        <button type="button" class="btn btn-primary mt-3" id="add-payment">Add Another Payment</button>
                    </div>
                </div>

            </div>
        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const paymentContainer = document.getElementById('payment-container');
            const addPaymentButton = document.getElementById('add-payment');

            // Function to update the total payment value
            function updateTotalPayment() {
                const amountInputs = paymentContainer.querySelectorAll('input[name="amount[]"]');
                let total = 0;

                amountInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0; // Get the value or default to 0
                    total += value; // Sum the values
                });

                // Update the total payment value in the footer
                document.getElementById('total-payment-val').innerText = 'Rp ' + total.toFixed(2);
            }

            // Event listener for amount input changes
            paymentContainer.addEventListener('input', function(e) {
                if (e.target.name === 'amount[]') {
                    updateTotalPayment(); // Update total when amount changes
                }
            });

            // Add payment entry
            addPaymentButton.addEventListener('click', function() {
                const paymentEntry = document.createElement('tr');
                paymentEntry.classList.add('payment-entry');
                paymentEntry.innerHTML = `
                    <td>
                        <input type="date" class="form-control form-control-sm" name="payment_date[]" required>
                    </td>
                    <td>
                        <select class="form-control form-control-sm" id="payment-select" name="payment_method_id[]" required>
                            @foreach ($methods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="input-group has-validation input-group-sm">
                            <span class="input-group-text" id="product-price-addon">Rp</span>
                            <input type="number" class="form-control" name="amount[]" placeholder="Enter amount" required>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-payment"><i class="ri-delete-bin-line"></i></button>
                    </td>
                `;
                paymentContainer.appendChild(paymentEntry);

                // Update total after adding a new payment entry
                updateTotalPayment();

                // Remove payment entry
                paymentEntry.querySelector('.remove-payment').addEventListener('click', function() {
                    paymentEntry.remove();
                    updateTotalPayment(); // Update total after removing a payment entry
                });
            });

            // Initial total calculation
            updateTotalPayment();
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addProductButton = document.getElementById('add-product');
            const productTableBody = document.getElementById(
                'product-table'); // Select the tbody of the product table
            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'decimal',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(value);
            }

            addProductButton.addEventListener('click', function() {
                // Create a new row for the product entry
                const newRow = document.createElement('tr');
                const categorySelect = document.getElementById('category-select');
                const typeSelect = document.getElementById('type-select');
                const productSelect = document.getElementById('product-select');
                const quantityInput = document.getElementById('quantity-input');
                const buyingPriceInput = document.getElementById('buying_price');

                // Get selected values
                const selectedCategory = categorySelect.options[categorySelect.selectedIndex].text;
                const selectedType = typeSelect.options[typeSelect.selectedIndex].text;
                const selectedProduct = productSelect.options[productSelect.selectedIndex].text;
                const quantity = quantityInput.value;
                const totalPrice = buyingPriceInput.value;

                const realPrice = parseInt(totalPrice.split('.').join(""))

                newRow.innerHTML = `
                    <td>${selectedCategory}</td>
                    <td>${selectedType}</td>
                    <td>${selectedProduct}</td>
                    <td>${quantity}</td>
                    <td>Rp ${formatCurrency(realPrice/quantity)}</td>
                    <td>Rp ${totalPrice}</td>
                    <td>
                        <button type="button" class="btn btn-danger remove-product">Remove</button>
                    </td>
                `;
                const hiddenInputs = `
                <input type="hidden" name="product_id[]" value="${productSelect.value}">
                <input type="hidden" name="quantity[]" value="${quantity}">
                `;
                newRow.innerHTML += hiddenInputs; // Append hidden inputs to the row
                productTableBody.appendChild(newRow);

                // Clear the input fields after adding the product
                categorySelect.selectedIndex = 0; // Reset to default
                quantityInput.value = ''; // Clear quantity input
                buyingPriceInput.value = ''; // Clear buying price input

                // Remove product entry
                newRow.querySelector('.remove-product').addEventListener('click', function() {
                    newRow.remove();
                });
            });
        });
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const productSelect = document.getElementById('product-select');
            const typeSelect = document.getElementById('type-select');
            const categorySelect = document.getElementById('category-select');

            const quantityInput = document.getElementById('quantity-input');
            const customerSelect = document.getElementById('customer-select');
            const buyingPriceInput = document.getElementById('buying_price');
            const orderStatusSelect = document.getElementById('order-status-select');
            const stockItem = document.getElementById('stock_item');

            const customerContainer = document.getElementById('customer-container');
            const buyRadio = document.getElementById('buy');
            const sellRadio = document.getElementById('sell');

            let order_type = ""
            buyRadio.addEventListener('change', function() {
                if (this.checked) {
                    customerContainer.style.display = 'none';
                    fetchStatuses('supplier')
                    order_type = 'supplier'
                }
            });

            sellRadio.addEventListener('change', function() {
                if (this.checked) {
                    customerContainer.style.display = 'block';
                    fetchStatuses('customer')
                    order_type = 'customer'
                }
            });

            const fetchStatuses = (enum_type) => {
                fetch(`http://localhost:8000/api/order_statuses/${enum_type}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Clear existing options
                        orderStatusSelect.innerHTML =
                            '<option value="">Select Status</option>'; // Reset to default option

                        // Populate the select with options from the fetched data
                        data.forEach(type => {
                            const option = document.createElement('option');
                            option.value = type.id; // Assuming each type has an 'id' field
                            option.textContent = type
                                .name; // Assuming each type has a 'name' field
                            orderStatusSelect.appendChild(option);
                        });
                    })
            }

            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'decimal',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(value);
            }


            // Update total price when quantity changes
            quantityInput.addEventListener('input', function() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute(
                    'data-price')); // Get the price as a float
                const quantity = parseInt(this.value) || 1; // Get the quantity, default to 1 if empty
                const totalPrice = price * quantity; // Calculate total price
                buyingPriceInput.value = formatCurrency(totalPrice); // Update the buying price input value
                const productStock = parseInt(selectedOption.getAttribute(
                    'data-stock'));

                if (order_type == 'customer') {
                    stockItem.innerHTML = `(Max ${productStock})`
                    if (quantity > productStock) {
                        this.value = productStock
                    }
                }
                if (quantity < 1) {
                    this.value = 1
                }
            });

            // Fetch product types when the category changes
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value; // Get the selected category ID
                fetch(`http://localhost:8000/api/product_category_types/${categoryId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Fetched product types:", data); // Debugging log
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


            // Fetch products when the type changes
            typeSelect.addEventListener('change', function() {
                const typeID = this.value; // Get the selected type ID
                console.log("Selected type ID:", typeID); // Debugging log
                fetch(`http://localhost:8000/api/product/${typeID}`) // Adjust the URL to your route
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Fetched products:", data); // Debugging log
                        // Clear existing options
                        productSelect.innerHTML =
                            '<option value="">Select Product</option>'; // Reset to default option


                        // Populate the select with options from the fetched data
                        data.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product
                                .id; // Assuming each product has an 'id' field
                            option.textContent = product
                                .name; // Assuming each product has a 'name' field
                            if (order_type.length > 0) {
                                if (order_type == 'supplier') {
                                    option.setAttribute('data-price', product
                                        .buying_price); // Add data-price attribute
                                }
                                if (order_type == 'customer') {
                                    option.setAttribute('data-price', product
                                        .sell_price); // Add data-price attribute
                                }
                            }
                            option.setAttribute('data-stock', product
                                .stock); // Add data-stock attribute

                            productSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                    });
            });
            // Trigger the initial fetch for categories
            categorySelect.dispatchEvent(new Event('change'));
            // typeSelect.dispatchEvent(new Event('change'));


        });
    </script>
@endsection
