@extends('layouts.master')
@section('title')
    Orders
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/nouislider/nouislider.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
@endsection
{{-- @dd($orders[0]->image) --}}
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Ecommerce
        @endslot
        @slot('title')
            Orders
        @endslot
    @endcomponent
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-xl-12">
            <div>
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row g-4">
                            <div class="col-sm-auto">
                                <div>
                                    <a href="orders/create" class="btn btn-success" id="addproduct-btn"><i
                                            class="ri-add-line align-bottom me-1"></i> Add Order</a>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control" id="searchProductList"
                                            placeholder="Search Products...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                                    <li class="nav-item ">
                                        <a class="nav-link active fw-semibold" data-bs-toggle="tab"
                                            href="#productnav-published" role="tab">
                                            Buy
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link fw-semibold" data-bs-toggle="tab" href="nav#productnav-draft"
                                            role="tab">
                                            Sell
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <div id="selection-element">
                                    <div class="my-n1 d-flex align-items-center text-muted">
                                        Select <div id="select-content" class="text-body fw-semibold px-1"></div> Result
                                        <button type="button" class="btn btn-link link-danger p-0 ms-3"
                                            data-bs-toggle="modal" data-bs-target="#removeItemModal">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">

                        <div class="tab-content text-muted">
                            <!-- end tab pane -->

                            <div class="tab-pane active" id="productnav-published" role="tabpanel">
                                <div class="live-preview">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Produk</th>
                                                    <th scope="col">Kuantitas</th>
                                                    <th scope="col">Harga</th>
                                                    <th scope="col">Harga Total</th>
                                                    <th scope="col">Tanggal Pesan</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($orders_buy as $order)
                                                    <tr>
                                                        <th scope="row"><a href="#"
                                                                class="fw-medium">#{{ $order->id }}</a></th>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0 me-3">
                                                                    <div class="avatar-sm bg-light rounded p-1">
                                                                        <img src="{{ $order->product->image ? asset('storage/' . $order->product->image->url) : asset('storage/images/default-case.jpg') }}"
                                                                            alt=""
                                                                            class="img-fluid d-block object-cover">
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-14 mb-1">
                                                                        <a href="apps-ecommerce-product-details"
                                                                            class="text-dark">{{ $order->product->name }}</a>
                                                                    </h5>
                                                                    {{-- <p class="text-muted mb-0">Category : <span
                                                                            class="fw-medium">row.product.category</span>
                                                                    </p> --}}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $order->quantity }}</td>
                                                        <td>Rp {{ number_format($order->product->sell_price, 0, ',', '.') }}
                                                        </td>
                                                        <td>Rp {{ number_format(($order->product->sell_price* $order->quantity), 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}
                                                        </td>
                                                        <td>{{ $order->status->name }}</td>
                                                        <td>
                                                            <ul class="list-inline hstack gap-2 mb-0">
                                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="View">
                                                                    <a href="#" class="text-primary d-inline-block"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#viewPaymentsModal"
                                                                        onclick="showPaymentDetails({{ json_encode($order->payments) }})">
                                                                        <i class="ri-eye-fill fs-16"></i>
                                                                    </a>
                                                                </li>


                                                                <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="Edit">
                                                                    <a href="{{ url('orders/' . $order->id . '/edit') }}"
                                                                        class="link-success fs-15">
                                                                        <i class="ri-edit-2-line"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="Remove">
                                                                    <a class="text-danger d-inline-block remove-item-btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#removeItemModal{{ $order->id }}">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </a>
                                                                    <div id="removeItemModal{{ $order->id }}"
                                                                        class="modal fade zoomIn" tabindex="-1"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"
                                                                                        id="btn-close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="mt-2 text-center">
                                                                                        <lord-icon
                                                                                            src="https://cdn.lordicon.com/gsqxdxog.json"
                                                                                            trigger="loop"
                                                                                            colors="primary:#f7b84b,secondary:#f06548"
                                                                                            style="width:100px;height:100px"></lord-icon>
                                                                                        <div
                                                                                            class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                                            <h4>Delete Item ?</h4>
                                                                                            <p
                                                                                                class="text-muted mx-4 mb-0">
                                                                                                Are
                                                                                                you Sure You want to Remove
                                                                                                this
                                                                                                Product ?</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                                                        <button type="button"
                                                                                            class="btn w-sm btn-light"
                                                                                            data-bs-dismiss="modal">Close</button>
                                                                                        <form
                                                                                            action="{{ url('orders/' . $order->id) }}"
                                                                                            method="POST">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit"
                                                                                                class="btn btn-danger">Delete</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>

                                                                            </div><!-- /.modal-content -->
                                                                        </div><!-- /.modal-dialog -->
                                                                    </div><!-- /.modal -->
                                                                </li>
                                                            </ul>

                                                        </td>

                                                    </tr>
                                                @empty
                                                    Empty
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab pane -->

                            <div class="tab-pane" id="productnav-draft" role="tabpanel">
                                <div class="live-preview">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Customer</th>
                                                    <th scope="col">Produk</th>
                                                    <th scope="col">Kuantitas</th>
                                                    <th scope="col">Harga</th>
                                                    <th scope="col">Harga Total</th>
                                                    <th scope="col">Tanggal Pesan</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Progress Pembayaran</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($orders_sell as $order)
                                                    <tr>
                                                        <th scope="row"><a href="#"
                                                                class="fw-medium">#{{ $order->id }}</a></th>
                                                        <td>{{ $order->participant->name }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0 me-3">
                                                                    <div class="avatar-sm bg-light rounded p-1">
                                                                        <img src="{{ $order->product->image ? asset('storage/' . $order->product->image->url) : asset('storage/images/default-case.jpg') }}"
                                                                            alt=""
                                                                            class="img-fluid d-block object-cover">
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-14 mb-1">
                                                                        <a href="apps-ecommerce-product-details"
                                                                            class="text-dark">{{ $order->product->name }}</a>
                                                                    </h5>
                                                                    {{-- <p class="text-muted mb-0">Category : <span
                                                                            class="fw-medium">row.product.category</span>
                                                                    </p> --}}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $order->quantity }}</td>
                                                        <td>Rp
                                                            {{ number_format($order->product->sell_price, 0, ',', '.') }}
                                                        </td>
                                                        <td>Rp {{ number_format(($order->product->sell_price* $order->quantity), 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}
                                                        </td>
                                                        <td>{{ $order->status->name }}</td>
                                                        <td>
                                                            <div class="flex-grow-1">
                                                                <div class="progress animated-progress custom-progress progress-label">
                                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{$order->getPaymentProgress()}}%"
                                                                        aria-valuenow="{{$order->getPaymentProgress()}}" aria-valuemin="0" aria-valuemax="100">
                                                                        <div class="label">{{$order->getPaymentProgress()}}%</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <ul class="list-inline hstack gap-2 mb-0">
                                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="View">
                                                                    <a href="#" class="text-primary d-inline-block"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#viewPaymentsModal"
                                                                        onclick="showPaymentDetails({{ json_encode($order->payments) }})">
                                                                        <i class="ri-eye-fill fs-16"></i>
                                                                    </a>
                                                                </li>


                                                                <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="Edit">
                                                                    <a href="{{ url('orders/' . $order->id . '/edit') }}"
                                                                        class="link-success fs-15">
                                                                        <i class="ri-edit-2-line"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="Remove">
                                                                    <a class="text-danger d-inline-block remove-item-btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#removeItemModal{{ $order->id }}">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </a>
                                                                    <div id="removeItemModal{{ $order->id }}"
                                                                        class="modal fade zoomIn" tabindex="-1"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"
                                                                                        id="btn-close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="mt-2 text-center">
                                                                                        <lord-icon
                                                                                            src="https://cdn.lordicon.com/gsqxdxog.json"
                                                                                            trigger="loop"
                                                                                            colors="primary:#f7b84b,secondary:#f06548"
                                                                                            style="width:100px;height:100px"></lord-icon>
                                                                                        <div
                                                                                            class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                                            <h4>Delete Item ?</h4>
                                                                                            <p
                                                                                                class="text-muted mx-4 mb-0">
                                                                                                Are
                                                                                                you Sure You want to Remove
                                                                                                this
                                                                                                Product ?</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                                                        <button type="button"
                                                                                            class="btn w-sm btn-light"
                                                                                            data-bs-dismiss="modal">Close</button>
                                                                                        <form
                                                                                            action="{{ url('orders/' . $order->id) }}"
                                                                                            method="POST">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit"
                                                                                                class="btn btn-danger">Delete</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>

                                                                            </div><!-- /.modal-content -->
                                                                        </div><!-- /.modal-dialog -->
                                                                    </div><!-- /.modal -->
                                                                </li>
                                                            </ul>

                                                        </td>


                                                    </tr>
                                                @empty
                                                    Empty
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->

                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    <!-- View Payment Modal -->
    <!-- View Payments Modal -->
    <div class="modal fade" id="viewPaymentsModal" tabindex="-1" aria-labelledby="viewPaymentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPaymentsModalLabel">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Payment ID</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Payment Date</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Shipping Amount</th>
                                <th scope="col">Overhead Amount</th>
                            </tr>
                        </thead>
                        <tbody id="paymentsTableBody">
                            <!-- Payment rows will be populated here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    <!-- removeItemModal -->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/wnumb/wNumb.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="https://unpkg.com/gridjs/plugins/selection/dist/selection.umd.js"></script>
    <script>
        function showPaymentDetails(payments) {
            const tableBody = document.getElementById('paymentsTableBody');
            tableBody.innerHTML = ''; // Clear previous entries

            payments.forEach(payment => {
                const amountShipping = payment.amount_shipping !== null ? payment.amount_shipping.toLocaleString(
                    'id-ID') : '0';
                const amountOverhead = payment.amount_overhead !== null ? payment.amount_overhead.toLocaleString(
                    'id-ID') : '0';

                const row = `
            <tr>
                <td>${payment.id}</td>
                <td>${payment.order_id}</td>
                <td>${payment.payment_date}</td>
                <td>Rp ${payment.amount.toLocaleString('id-ID')}</td>
                <td>Rp ${amountShipping}</td>
                <td>Rp ${amountOverhead}</td>
            </tr>
        `;
                tableBody.innerHTML += row; // Append new row
            });
        }
    </script>




    {{-- <script src="{{ URL::asset('build/js/pages/ecommerce-product-list.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
