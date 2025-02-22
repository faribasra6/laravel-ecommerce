@extends('shop.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account')}}">My Account</a></li>
                    <li class="breadcrumb-item">Order no {{$order->id}}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('shop.profile.account-panel')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">My Orders</h2>
                        </div>

                        <div class="card-body pb-0">
                            <!-- Info -->
                            <div class="card card-sm">
                                <div class="card-body bg-light mb-3">
                                    <div class="row">
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Order No:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                            {{$order->id}}
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Shipped date:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                <time datetime="{{ $order->estimated_delivery ? $order->estimated_delivery->format('Y-m-d') : '' }}">
                                                    @if($order->estimated_delivery)
                                                        {{ $order->estimated_delivery->format('F j, Y') }} <!-- This will display the date in a human-readable format -->
                                                    @else
                                                        <span class="text-muted">Not Available</span> <!-- Optional: show "Not Available" if no date is set -->
                                                    @endif
                                                </time>
                                                
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Status:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                                <span class="badge 
                                                @if($order->status == 'pending') 
                                                bg-warning 
                                                @elseif($order->status == 'delivered') 
                                                bg-success 
                                                @elseif($order->status == 'canceled') 
                                                bg-danger 
                                                @elseif($order->status == 'shipped') 
                                                bg-info 
                                                @else 
                                                bg-secondary 
                                                @endif">
                                                {{ $order->status }}
                                            </span>
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Order Amount:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                            AED {{ number_format( $order->grand_total, 2)}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer p-3">

                            <!-- Heading -->
                            <h6 class="mb-7 h5 mt-4">Order Items ({{ count($orderitems)}})</h6>

                            <!-- Divider -->
                            <hr class="my-3">

                            <!-- List group -->
                            <ul>
                                @foreach( $orderitems as $item)
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-md-3 col-xl-2">
                                            <!-- Image -->
                                            @php
                                                $img = getProductImage($item->product_id)
                                            @endphp
                                            @if($img)
                                            <img src="{{ asset('storage/'. $img->path) }}" alt="{{ $item->name }}" class="img-fluid">
                                            @else
                                            <img src="{{ asset('storage/image.png') }}" alt="No Image" class="img-fluid">
                                            @endif
                                            
                                        </div>
                                        <div class="col">
                                            <!-- Title -->
                                            <p class="mb-4 fs-sm fw-bold">
                                                <a class="text-body" href="product.html">{{$item->name}} x {{$item->qty}}</a> <br>
                                                <span class="text-muted">AED {{$item->total}}</span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>                      
                    </div>
                    
                    <div class="card card-lg mb-5 mt-3">
                        <div class="card-body">
                            <!-- Heading -->
                            <h6 class="mt-0 mb-3 h5">Order Total</h6>

                            <!-- List group -->
                            <ul>
                                <li class="list-group-item d-flex">
                                    <span>Subtotal</span>
                                    <span class="ms-auto">AED {{number_format($order->subtotal, 2)}}</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Discount {!! !empty($order->coupon_code) 
                                        ? '<span class="badge bg-info">' . $order->coupon_code . '</span>' 
                                        : '' !!}
                                    </span>
                                    <span class="ms-auto">AED {{number_format($order->discount, 2)}}</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Shipping</span>
                                    <span class="ms-auto">AED {{number_format($order->shipping, 2)}}</span>
                                </li>
                                <li class="list-group-item d-flex fs-lg fw-bold">
                                    <span>Total</span>
                                    <span class="ms-auto">AED {{number_format($order->grand_total, 2)}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection