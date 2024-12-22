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
    <form id="createproduct-form" action="{{ '/orders/' . $order->id }}" method="POST" enctype="multipart/form-data"
        autocomplete="off" class="needs-validation">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="product-select" class="form-label">Product</label>
                            <select class="js-example-basic-single" id="product-select" name="product_id" data-choices
                                data-choices-search-false>
                                @foreach ($products as $product)
                                    @if ($order->product->id == $product->id)
                                        <option selected value="{{ $product->id }}"
                                            data-price="{{ $product->buying_price }}">
                                            {{ $product->name }} - {{ $product->supplier->name }}
                                        </option>
                                    @else
                                        <option value="{{ $product->id }}" data-price="{{ $product->buying_price }}">
                                            {{ $product->name }} - {{ $product->supplier->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="row">

                                <div class="col-lg-4">
                                    <label for="product-select" class="form-label">Order Type</label>
                                    <div class="">
                                        <div class="form-check form-check-inline">
                                            @if ($order->order_type == 'buy')
                                                <input checked class="form-check-input" type="radio" name="order_type"
                                                    id="buy" value="buy">
                                            @else
                                                <input class="form-check-input" type="radio" name="order_type"
                                                    id="buy" value="buy">
                                            @endif
                                            <label class="form-check-label" for="buy">Beli dari toko</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            @if ($order->order_type == 'sell')
                                                <input checked class="form-check-input" type="radio" name="order_type"
                                                    id="sell" value="sell">
                                            @else
                                                <input class="form-check-input" type="radio" name="order_type"
                                                    id="sell" value="sell">
                                            @endif
                                            <label class="form-check-label" for="sell">Jual ke customer</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <label for="payment-date-input" class="form-label">Tanggal Pembayaran</label>
                                    <input type="date" class="form-control" name="order_date" id="payment-date-input"
                                        value="{{ old('order_date', $order->order_date ? $order->order_date->format('Y-m-d') : '') }}">
                                </div>

                                <div class="col-lg-3">
                                    <label for="customer-select" class="form-label">Order Status</label>
                                    <select class="js-example-basic-single" name="order_status_id" id="order-status-select">
                                        @foreach ($order_statuses as $status)
                                            @if ($order->order_status_id == $status->id)
                                                <option selected value="{{ $status->id }}"
                                                    data-visibility="{{ $status->order_visibility }}">
                                                    {{ $status->name }}
                                                </option>
                                            @else
                                                <option value="{{ $status->id }}"
                                                    data-visibility="{{ $status->order_visibility }}">
                                                    {{ $status->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6" id="customer-container" style="display: none">
                            <label for="customer-select" class="form-label">Customer</label>
                            <select class="js-example-basic-single" name="participant_id">
                                @foreach ($customers as $customer)
                                    @if ($order->participant_id == $customer->id)
                                        <option selected value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @else
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endif
                                @endforeach
                            </select>

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
                                            <label class="form-label" for="quantity-input">Quantity</label>
                                            <input type="number" class="form-control" id="quantity-input"
                                                name="quantity" placeholder="quantity" value="{{old('quantity',$order->quantity)}}">
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
                            </div>

                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->
                    </div>
                    <!-- end card body -->
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <table class="table" id="payment-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Jumlah Pembayaran</th>
                                    <th>Biaya Pengiriman</th>
                                    <th>Biaya Tambahan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="payment-container">
                                @foreach ($order->payments as $payment)
                                    <tr class="payment-entry">
                                        <td>
                                            <input type="date" class="form-control" name="payment_date[]" value="{{ $payment->payment_date->format('Y-m-d') }}" required>
                                        </td>
                                        <td>
                                            <div class="input-group has-validation mb-3">
                                                <span class="input-group-text" id="product-price-addon">Rp</span>
                                                <input type="number" class="form-control" name="amount[]" value="{{ $payment->amount }}" placeholder="Enter amount" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group has-validation mb-3">
                                                <span class="input-group-text" id="product-price-addon">Rp</span>
                                                <input type="number" class="form-control" name="amount_shipping[]" value="{{ $payment->amount_shipping }}" placeholder="Enter shipping amount">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group has-validation mb-3">
                                                <span class="input-group-text" id="product-price-addon">Rp</span>
                                                <input type="number" class="form-control" name="amount_overhead[]" value="{{ $payment->amount_overhead }}" placeholder="Enter overhead amount">
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-payment">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary" id="add-payment">Add Another Payment</button>
                    </div>
                </div>


                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">Submit</button>
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

            addPaymentButton.addEventListener('click', function() {
                const paymentEntry = document.createElement('tr');
                paymentEntry.classList.add('payment-entry');
                paymentEntry.innerHTML = `
                    <td>
                        <input type="date" class="form-control" name="payment_date[]" required>
                    </td>
                    <td>
                        <div class="input-group has-validation mb-3">
                            <span class="input-group-text" id="product-price-addon">Rp</span>
                            <input type="number" class="form-control" name="amount[]" placeholder="Enter amount" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group has-validation mb-3">
                            <span class="input-group-text" id="product-price-addon">Rp</span>
                            <input type="number" class="form-control" name="amount_shipping[]" placeholder="Enter shipping amount">
                        </div>
                    </td>
                    <td>
                        <div class="input-group has-validation mb-3">
                            <span class="input-group-text" id="product-price-addon">Rp</span>
                            <input type="number" class="form-control" name="amount_overhead[]" placeholder="Enter overhead amount">
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-payment">Remove</button>
                    </td>
                `;
                paymentContainer.appendChild(paymentEntry);
            });

            // Event delegation for removing payment entries
            paymentContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-payment')) {
                    e.target.closest('tr').remove();
                }
            });
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const quantityInput = document.getElementById('quantity-input');
            const productSelect = document.getElementById('product-select');
            const customerSelect = document.getElementById('customer-select');
            const buyingPriceInput = document.getElementById('buying_price');
            const orderStatusSelect = document.getElementById('order-status-select');

            const customerContainer = document.getElementById('customer-container');
            const buyRadio = document.getElementById('buy');
            const sellRadio = document.getElementById('sell');

            // Function to update the display based on the selected radio button
            function updateCustomerContainerDisplay() {
                if (buyRadio.checked) {
                    customerContainer.style.display = 'none';
                    filterOrderStatus('supplier'); // Show relevant order statuses
                } else if (sellRadio.checked) {
                    customerContainer.style.display = 'block';
                    filterOrderStatus('customer'); // Show relevant order statuses
                }
            }

            // Initial check to set the display based on the current order type
            updateCustomerContainerDisplay();

            // Add event listeners for radio button changes
            buyRadio.addEventListener('change', updateCustomerContainerDisplay);
            sellRadio.addEventListener('change', updateCustomerContainerDisplay);

            function filterOrderStatus(...allowedVisibilities) {
                console.log("allowed ", allowedVisibilities)
                const options = orderStatusSelect.options;
                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    const visibility = option.getAttribute('data-visibility');
                    console.log("visibility ", visibility)
                    option.style.display = allowedVisibilities.includes(visibility) ? 'block' : 'none';
                }
                // Reset the selected option if it's not visible
                if (!allowedVisibilities.includes(options[orderStatusSelect.selectedIndex].getAttribute(
                        'data-visibility'))) {
                    orderStatusSelect.selectedIndex = 0; // Reset to the first option
                }
            }

            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'decimal',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(value);
            }

            // Update buying price when a product is selected
            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute(
                    'data-price')); // Get the price as a float
                const quantity = parseInt(quantityInput.value) ||
                    1; // Get the quantity, default to 1 if empty
                const totalPrice = price * quantity; // Calculate total price
                buyingPriceInput.value = formatCurrency(totalPrice); // Set the buying price input value
            });

            // Update total price when quantity changes
            quantityInput.addEventListener('input', function() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute(
                    'data-price')); // Get the price as a float
                const quantity = parseInt(this.value) || 1; // Get the quantity, default to 1 if empty
                const totalPrice = price * quantity; // Calculate total price
                buyingPriceInput.value = formatCurrency(totalPrice); // Update the buying price input value
            });


            productSelect.dispatchEvent(new Event('change'));

        });
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection
