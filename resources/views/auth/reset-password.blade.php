<x-guest-layout>
    @section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item">Forget Password</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-10">
            <div class="container">
                <div class="login-form">
                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" />

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- <input type="hidden" name="token" value="{{ $request->route('token') }}"> --}}
                        <input type="hidden" name="token" value="{{ $token }}">

                        <h4 class="modal-title">Reset Password</h4>

                        <div class="form-group">
                            <x-label for="email" value="{{ __('Email') }}" />
                            {{-- <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" /> --}}
                            <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $email)" required autofocus autocomplete="username" />
                        </div>

                        <div class="form-group">
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                        </div>

                        <div class="form-group">
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                        </div>
                        <div id="password-error" style="color: red; display: none;">Passwords do not match.</div>

                        <input type="submit" class="btn btn-dark btn-block btn-lg" value="Reset Password">
                    </form>

                    <div class="text-center small">Back to <a href="{{ route('login') }}">Login</a></div>
                </div>
            </div>
        </section>
    </main>
    @endsection
    @section('customScript')
    <script>
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');
        const errorElement = document.getElementById('password-error');
        const submitButton = document.querySelector('button[type="submit"]');
    
        function validatePasswords() {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;
    
            if (password !== confirmPassword) {
                errorElement.style.display = 'block';
                errorElement.textContent = 'Passwords do not match.';
                submitButton.disabled = true; // Disable the submit button
            } else {
                errorElement.style.display = 'none';
                submitButton.disabled = false; // Enable the submit button
            }
        }
    
        // Add event listeners to both fields
        passwordField.addEventListener('input', validatePasswords);
        confirmPasswordField.addEventListener('input', validatePasswords);
    </script>
    @endsection
</x-guest-layout>
