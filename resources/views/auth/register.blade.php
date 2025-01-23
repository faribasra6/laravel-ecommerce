@extends('shop.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item">Register</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">    
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Phone (e.g., +9715XXXXXXX)" id="phone" name="phone" value="{{ old('phone') }}" required>
                        <div id="phoneError" class="text-danger"></div>
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Address" id="address" name="address" value="{{ old('address') }}">
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    {{-- @if (Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="{{ route('terms.show') }}" target="_blank">Terms of Service</a> and <a href="{{ route('policy.show') }}" target="_blank">Privacy Policy</a>.
                                </label>
                            </div>
                            @error('terms')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif --}}
                    <button type="submit" class="btn btn-dark btn-block btn-lg">Register</button>
                </form>		
                <div class="text-center small">Already have an account? <a href="{{ route('login') }}">Login Now</a></div>
            </div>
        </div>
    </section>
</main>
@endsection
@section('customScript')
<script>
    document.getElementById('phone').addEventListener('input', function () {
        const phoneInput = this.value;
        const phoneError = document.getElementById('phoneError');
        const uaePhoneRegex = /^(05\d{7}|\+9715\d{7})$/; // Exact match for 05XXXXXXX or +9715XXXXXXX

        if (!uaePhoneRegex.test(phoneInput)) {
            phoneError.textContent = 'Please enter a valid UAE phone number (e.g., 05XXXXXXX or +9715XXXXXXX).';
        } else {
            phoneError.textContent = '';
        }
    });
</script>

@endsection