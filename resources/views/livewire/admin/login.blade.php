<div>
    <div class="container-fluid bg-light d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg border-0" style="width: 28rem; border-radius: 1rem;">
            <div class="card-body p-5">
                <!-- Logo Section -->
                <div class="text-center mb-4">
                    <a href="https://www.youtube.com/@RonaldCodes23" target="_blank">
                        <img src="{{ asset('assets/images/RonaldCodesLogo.png') }}" alt="Ronald Codes Logo"
                            class="img-fluid mb-3 rounded-circle" style="width: 100px; cursor: pointer;">
                    </a>
                    <h3 class="font-weight-bold text-dark">Welcome Back!</h3>
                    <p class="text-muted">Log in to continue.</p>
                </div>

                <!-- Login Form -->
                <form wire:submit.prevent="save">
                    <div class="form-group">
                        <label for="email" class="font-weight-bold text-muted">Email</label>
                        <input wire:model="email" type="email" class="form-control form-control-lg"
                            placeholder="Enter your email">
                        @error('email')
                            <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="font-weight-bold text-muted">Password</label>
                        <input wire:model="password" type="password" class="form-control form-control-lg"
                            placeholder="Enter your password">
                        @error('password')
                            <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger small mt-3">{{ session('error') }}</div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                        Login
                    </button>
                </form>

                <!-- Footer Section -->
                <div class="text-center mt-4">
                    <p class="text-muted">
                        Don't have an account?
                        <a href="#" class="text-primary font-weight-bold">Sign Up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>


</div>
