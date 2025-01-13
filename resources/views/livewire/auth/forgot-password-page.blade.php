<div>
    <div class="container-fluid bg-light d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg border-0" style="width: 28rem; border-radius: 1rem;">
            <div class="card-body p-5">
                <!-- Logo Section -->
                <div class="text-center mb-4">
                    <a href="" >
                        <img src="{{ asset('assets/images/RonaldCodesLogo.png') }}" alt="Ronald Codes Logo"
                            class="img-fluid mb-3 rounded-circle" style="width: 100px; cursor: pointer;">
                    </a>
                    <h3 class="font-weight-bold text-dark">Forgot Password?</h3>
                    <p class="text-muted">Enter your email to reset your password.</p>
                </div>

                <!-- Forgot Password Form -->
                <form wire:submit.prevent="sendResetLink">
                    <div class="form-group">
                        <label for="email" class="font-weight-bold text-muted">Email</label>
                        <input wire:model="email" type="email" class="form-control form-control-lg"
                            placeholder="Enter your email">
                        @error('email')
                            <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success small mt-3">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger small mt-3">{{ session('error') }}</div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                        Send Reset Link
                    </button>
                </form>

                <!-- Footer Section with Single-Line Links -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        <a wire:navigate href="/login" class="text-primary font-weight-bold">Back to Login</a>
                        <span class="mx-2">|</span>
                        <a wire:navigate href="/register" class="text-primary font-weight-bold">Sign Up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
