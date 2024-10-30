@extends('layouts.master')
@section('title')
    {{ __('View order details') }} # {{ $order->id }}
@endsection
@section('css')
    <style type="text/css">
        #maporder {
            height: 400px;
            width: 100%;
        }

        #contentmap {
            font-size: 12pt;
            font-family: "Cairo", sans-serif;

        }

        .nostaractive {
            color: #e1e6f1 !important;
        }

        span#lg-share {
            display: none !important;
        }

        .cursorimg:hover {
            cursor: pointer;
        }
    </style>
    <!-- Internal Gallery css -->
    <link href="{{ URL::asset('assets/plugins/gallery/gallery.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--- Internal Sweet-Alert css-->
    <link href="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->

    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('View order details') }} # {{ $order->id }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0"> </span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0 ml-3">
                <div class="btn-group dropdown">
                    <button type="button" class="btn btn-danger" id="deleteOrderBtn"
                        data-url="{{ route('orders.destroy', $order->id) }}" data-toggle="modal" data-target="#deleteModal"
                        data-placement="top" data-toggle="tooltip" title="{{ __('delete') }} ">
                        {{ __('delete') }} </button>
                </div>
            </div>

            <div class="mb-3 mb-xl-0 ml-3">
                <div class="btn-group dropdown">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalChangeStatusOrder"
                        data-placement="top" data-toggle="tooltip" title="{{ __('Change Order Status') }} ">
                        {{ __('Change Order Status') }} </button>
                </div>
            </div>



            <div class="pr-1 mb-3 mb-xl-0 ml-3">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary ">{{ __('back') }}</a>
            </div>

        </div>
    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <div class="modal fade " id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="deleteBookForm" enctype="multipart/form-data">
                @csrf
                @method('delete')
                <div class="modal-content ">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ __('delete') }}</h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        {{ __('Are you sure!') }}
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-danger" type="submit">{{ __('delete') }}</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal"
                            type="button">{{ __('Close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade " id="addBookModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('orderitems.store') }}" id="addBookModalvalid"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $order->id }}">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title"> {{ __('add_book') }}</h4>
                        <h5>{{ __('Change Order Status') }}</h5>
                    </div>
                    <div class="modal-body">

                        <div class="row row-sm">

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('book') }}: <span class="tx-danger">*</span></label>
                                    <div class="parsley-select " id="slWrapperis_active">
                                        <select class="form-control selectwithoutsearch"
                                            data-parsley-class-handler="#slWrapperis_active"
                                            data-parsley-errors-container="#slErrorContaineris_active"
                                            data-placeholder="{{ __('select') }}" id="status_id" name="book_id"
                                            required="">
                                            <option value="  ">
                                                {{ __('select') }}
                                            </option>
                                            @foreach ($books->reject(function ($book) use ($order) {
            return $order->orderItems->pluck('book_id')->contains($book->id);
        }) as $book)
                                                <option value="{{ $book->id }}">
                                                    {{ $book->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="slErrorContaineris_active">
                                            @if ($errors->ModalChangeStatusOrderbag->has('book_id'))
                                                <span
                                                    class="tx-danger">{{ $errors->ModalChangeStatusOrderbag->first('book_id') }}
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="qty">{{ __('qty') }}</label>
                                    <input class="form-control" type="number" name="qty" id="qty">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary" id="modal_changeorder_submit_button"
                            type="submit">{{ __('add_book') }}</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal"
                            type="button">{{ __('Close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade " id="ModalChangeStatusOrder" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="ModalChangeStatusOrdervalid" enctype="multipart/form-data"
                action="{{ route('orders.updateStatus') }}">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $order->id }}">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title"> {{ __('Order number') }} {{ $order->id }}</h4>
                        <h5>{{ __('Change Order Status') }}</h5>
                    </div>
                    <div class="modal-body">

                        <div class="row row-sm">

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Order Status') }}: <span
                                            class="tx-danger">*</span></label>
                                    <div class="parsley-select " id="slWrapperis_active">
                                        <select class="form-control selectwithoutsearch"
                                            data-parsley-class-handler="#slWrapperis_active"
                                            data-parsley-errors-container="#slErrorContaineris_active"
                                            data-placeholder="{{ __('select') }}" id="status_id" name="status_id"
                                            required="">
                                            <option value="  ">
                                                {{ __('select') }}
                                            </option>
                                            @foreach ($orderStatuses as $order_status)
                                                <option value="{{ $order_status['id'] }}"
                                                    {{ $order->status == $order_status['id'] ? 'selected' : '' }}>
                                                    {{ $order_status['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="slErrorContaineris_active">
                                            @if ($errors->ModalChangeStatusOrderbag->has('status_id'))
                                                <span
                                                    class="tx-danger">{{ $errors->ModalChangeStatusOrderbag->first('status_id') }}
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary" id="modal_changeorder_submit_button"
                            type="submit">{{ __('edit') }}</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal"
                            type="button">{{ __('Close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="row row-sm">
        <div class="col-lg-4">
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="pl-0">
                        <div class="main-profile-overview">
                            <div class="main-img-user profile-user">
                                <img alt=""
                                    src="{{ $order->user->image ? asset($order->user->image) : URL::asset('assets/img/faces/6.jpg') }}">
                            </div>
                            <div class="d-flex justify-content-between mg-b-5">
                                <div>
                                    <h5 class="main-profile-name">{{ $order->user->name }}</h5>
                                </div>
                            </div><br />

                            <hr class="mg-y-10">
                            <div class="table-responsive mg-t-20">
                                <table class="table table-invoice border text-md-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('number books') }}</td>
                                            <td colspan="2">{{ $order->orderItems->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Total Books') }}</td>
                                            <td colspan="2">{{ $order->sub_total }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Coupon Discount Value') }}</td>
                                            <td colspan="2" class="{{ $order->discount ? 'text-danger' : '' }}">
                                                {{ $order->discount }}
                                                {{ $order->discount ? '-' : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Delivery value') }}</td>
                                            <td colspan="2">{{ floatval($order->shipping) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('added tax') }}</td>
                                            <td colspan="2">{{ floatval($order->tax) }}</td>
                                        </tr>
                                        <tr>
                                            <td class=" tx-uppercase tx-bold tx-inverse">{{ __('total') }}
                                            </td>
                                            <td colspan="2">
                                                <h4 class="tx-primary tx-bold">{{ floatval($order->total) }}</h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- main-profile-overview -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row row-sm">
                <div class="col-sm-12 col-xl-6 col-lg-12 col-md-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="counter-status d-flex md-mb-0">
                                <div class="counter-icon bg-primary-transparent">
                                    <i class="icon-clock text-primary"></i>
                                </div>
                                <div class="mr-auto">
                                    <h2 class="tx-14 " style="font-weight:bold">{{ __('date') }}</h2>
                                    <h5 class="tx-15 mb-1 mt-1">
                                        {{ $order->created_at->format('d/m/Y') }}<br />{{ $order->created_at->format('h:i:s A') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-6 col-lg-12 col-md-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="counter-status d-flex md-mb-0">
                                <div class="counter-icon bg-danger-transparent">
                                    <i class="icon-rocket text-success"></i>
                                </div>
                                <div class="mr-auto">
                                    <h2 class="tx-14 mt-2" style="font-weight:bold">{{ __('Order Status') }}
                                    </h2>
                                    <h5 class="mb-0 tx-15 mt-1">
                                        {{ match ($order->status) {
                                            0 => 'pending',
                                            1 => 'complete',
                                            2 => 'return',
                                        } }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card">
                <div class="card-body">
                    <div class="tabs-menu ">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs profile navtab-custom panel-tabs">
                            <li class="active">
                                <a href="#home" data-toggle="tab" aria-expanded="true"> <span class="visible-xs"><i
                                            class="las la-shopping-cart tx-20 mr-1"></i></span> <span
                                        class="hidden-xs">{{ __('Order Books') }}</span> </a>
                            </li>
                            <li class="">
                                <a href="#profile" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i
                                            class="las la-map-marker-alt tx-20 mr-1"></i></span> <span
                                        class="hidden-xs">{{ __('Delivery Address') }}</span> </a>
                            </li>
                            <li class="">
                                <a href="#settings" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i
                                            class="las la-file-invoice-dollar tx-20 mr-1"></i></span> <span
                                        class="hidden-xs">{{ __('Payment Method') }}</span> </a>
                            </li>
                            {{-- <li class="">
                                <a href="#reviews" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i
                                            class="las la-file-invoice-dollar tx-20 mr-1"></i></span> <span
                                        class="hidden-xs">{{ __('reviews') }}</span> </a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="tab-content border-left border-bottom border-right border-top-0 p-4">
                        <div class="tab-pane active" id="home">
                            <!-- Shopping Cart-->
                            <div class="book-details table-responsive text-nowrap">
                                <table class="table table-bordered table-hover mb-0 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>{{ __('book') }}</th>
                                            <th class="w-150">{{ __('qty') }}</th>
                                            <th>{{ __('price') }}</th>
                                            <th>{{ __('total') }}</th>
                                            <th>{{ __('actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-items-body">
                                        @foreach ($order->orderItems as $order_item)
                                            <tr>
                                                <form action="{{ route('orderitems.update', $order_item) }}"
                                                    method="post">
                                                    @method('put')
                                                    @csrf
                                                    <td>
                                                        <div class="media">
                                                            <div class="card-aside-img">
                                                                <img src="{{ asset($order_item->book->image) }}"
                                                                    alt="img" class="h-60 w-60 book-image">
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="card-item-desc mt-0">
                                                                    <h6 class="font-weight-semibold mt-0 text-uppercase">
                                                                        <select name="book_id"
                                                                            class="form-control select2 book-select">
                                                                            @foreach ($books as $book)
                                                                                <option value="{{ $book->id }}"
                                                                                    @if ($order_item->book_id == $book->id) selected @endif>
                                                                                    {{ $book->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group" style="text-align: center">
                                                            <input type="number" name="qty"
                                                                class="form-control qty-input" style="width: 100px"
                                                                value="{{ $order_item->qty }}">
                                                        </div>
                                                    </td>
                                                    <td class="text-center text-lg text-medium">
                                                        <input type="number" name="price"
                                                            class="form-control price-input" style="width: 100px"
                                                            value="{{ $order_item->price }}">
                                                    </td>
                                                    <td class="text-center text-lg text-medium total">
                                                        {{ $order_item->qty * $order_item->price }}
                                                    </td>
                                                    <td>
                                                        <button type="submit"
                                                            class="btn btn-primary">{{ __('update') }}</button>
                                                        <a href="#" id="deletebookItem"
                                                            data-url="{{ route('orderitems.destroy', $order_item->id) }}"
                                                            data-toggle="modal" data-target="#deleteModal"
                                                            class="btn btn-danger">
                                                            {{ __('delete') }}
                                                        </a>

                                                    </td>
                                                </form>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div style="text-align: end;" class="mt-3">

                                    <!-- Button to Add New Row -->
                                    <button id="add_book" data-toggle="modal" data-target="#addBookModal"
                                        class="btn btn-success">{{ __('add_book') }}</button>
                                </div>



                            </div>
                        </div>
                        <div class="tab-pane" id="profile">
                            <p class="text-center my-3">{{ $order->address }}</p>

                        </div>
                        <div class="tab-pane" id="settings">
                            <div style="text-align: center" class="tx-16"><button type="button"
                                    class="btn btn-primary mx-2 button-icon mb-1 tx-14">
                                    {{ $order->payment_type ? 'Online' : 'Cash' }}
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane" id="reviews">
                            <div style="text-align: center" class="tx-16">
                                <h5> {{ __('stars') }} : {{ $order->orderReview->stars ?? 0 }}</h5>
                                <h5> {{ __('comment') }} : {{ $order->orderReview->comment ?? '' }}</h5>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ URL::asset('assets/plugins/select2/js/i18n/' . app()->getLocale() . '.js') }}"></script>
    <!--Internal  Parsley.min js -->
    <script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
    <!-- Internal Form-validation js -->
    <script src="{{ URL::asset('assets/plugins/parsleyjs/i18n/' . app()->getLocale() . '.js') }}"></script>

    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB556JrqytIxxt2hT5hkpLBQdUblve3w5U&language=ar&callback=initMap">
    </script>
    <!-- Internal Gallery js -->
    <script src="{{ URL::asset('assets/plugins/gallery/lightgallery-all.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/gallery/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/gallery.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Get CSRF token from meta tag
            const csrfToken = '{{ csrf_token() }}';

            // Event listener for dynamically updating book price, image, and total
            $(document).on('change', '.book-select', function() {
                const selectedBookId = $(this).val();
                const row = $(this).closest('tr');

                // Fetch book details via API
                fetch(`/mall/fetch-books/${selectedBookId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update the book image
                        row.find('.book-image').attr('src', data.image);

                        // Update the price input field
                        row.find('.price-input').val(data.price);

                        // Recalculate the total
                        const qty = row.find('.qty-input').val();
                        const total = qty * data.price;
                        row.find('.total').text(total);
                    })
                    .catch(error => console.error('Error fetching book details:', error));
            });

            // Event listener for qty and price input changes
            $(document).on('input', '.qty-input, .price-input', function() {
                const row = $(this).closest('tr');
                const qty = row.find('.qty-input').val();
                const price = row.find('.price-input').val();
                const total = qty * price;
                row.find('.total').text(total);
            });


            // Submit handler for dynamically added rows
            $(document).on('click', '.btn-store', function(e) {
                e.preventDefault();
                const uniqueId = $(this).attr('id').split('-')[1];
                const form = $(`#form-${uniqueId}`);
                form.submit(); // Submit the specific form
            });
        });

        $(document).on('click', '#deletebookItem', function() {
            // $('#deleteModal').modal('show');
            $('#deleteBookForm').attr('action', $(this).data('url'));
        });

        $(document).on('click', '#deleteOrderBtn', function() {
            // $('#deleteModal').modal('show');
            $('#deleteBookForm').attr('action', $(this).data('url'));
        });
    </script>
@endsection
