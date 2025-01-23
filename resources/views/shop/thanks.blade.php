@extends('shop.layouts.app');

@section('content')
<section class="container">
    <div class="col-md-12 text-center py-5">
        <div class="row">
            @if (Session::has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {!! Session::get('success') !!}  <!-- Changed from 'message' to 'success' -->
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    {{ Session::get('error') }}  <!-- Changed from 'message' to 'error' -->
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        <h1>Thank You!</h1>
        <p>Your Order Id is: {{ $orderId }}</p>
        
    
    </div>
</section>
@endsection