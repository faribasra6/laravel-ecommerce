@extends('shop.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item">Login</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-10">
        <div class="container">
            <div class="login-form">
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <h4 class="modal-title">Login to Your Account</h4>

                    <div class="form-group">
                        <input id="email" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>

                    <div class="form-group">
                        <input id="password" type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
                    </div>

                    <div class="form-group small">
                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">Forgot Password?</a>
                        @endif
                    </div>

                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Login">
                </form>

                <div class="text-center small">Don't have an account? <a href="{{ route('register') }}">Sign up</a></div>
            </div>
        </div>
    </section>
</main>
@endsection
