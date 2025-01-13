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
                    <h3 class="font-weight-bold text-dark">Reset Your Password</h3>
                    <p class="text-muted">Enter your new password below.</p>
                </div>

                <!-- Reset Password Form -->
                <form wire:submit.prevent="resetPassword">
                    <div class="form-group">
                        <label for="password" class="font-weight-bold text-muted">New Password</label>
                        <input wire:model="password" type="password" class="form-control form-control-lg"
                            placeholder="Enter your new password">
                        @error('password')
                            <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="font-weight-bold text-muted">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password" class="form-control form-control-lg"
                            placeholder="Confirm your new password">
                        @error('password_confirmation')
                            <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success small mt-3">{{ session('status') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger small mt-3">{{ session('error') }}</div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                        Reset Password
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
