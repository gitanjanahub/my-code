<div>
    <div class="container-fluid bg-light d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg border-0" style="width: 40rem; border-radius: 1rem;">
            <div class="card-body p-5">
                <!-- Logo Section -->
                <div class="text-center mb-4">
                    <a href="" >
                        <img src="{{ asset('assets/images/RonaldCodesLogo.png') }}" alt="Ronald Codes Logo"
                            class="img-fluid mb-3 rounded-circle" style="width: 100px; cursor: pointer;">
                    </a>
                    <h3 class="font-weight-bold text-dark">Create Your Account</h3>
                    <p class="text-muted">Sign up to get started.</p>
                </div>

                <!-- Register Form -->
                <form wire:submit.prevent="register">
                    <div class="row">
                        <!-- Full Name -->
                        <div class="form-group col-md-6">
                            <label for="name" class="font-weight-bold text-muted">Full Name</label>
                            <input wire:model="name" type="text" class="form-control form-control-lg"
                                placeholder="Enter your full name">
                            @error('name')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group col-md-6">
                            <label for="email" class="font-weight-bold text-muted">Email</label>
                            <input wire:model="email" type="email" class="form-control form-control-lg"
                                placeholder="Enter your email">
                            @error('email')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="form-group col-md-6">
                            <label for="password" class="font-weight-bold text-muted">Password</label>
                            <input wire:model="password" type="password" class="form-control form-control-lg"
                                placeholder="Enter your password">
                            @error('password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group col-md-6">
                            <label for="password_confirmation" class="font-weight-bold text-muted">Confirm Password</label>
                            <input wire:model="password_confirmation" type="password"
                                class="form-control form-control-lg" placeholder="Confirm your password">
                            @error('password_confirmation')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger small mt-3">{{ session('error') }}</div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                        Register
                    </button>
                </form>

                <!-- Footer Section with Single-Line Links -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Already have an account?
                        <a wire:navigate href="/login" class="text-primary font-weight-bold">Log In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
