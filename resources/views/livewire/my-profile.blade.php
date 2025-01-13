<div>
    <div class="container py-5">
        <h2 class="mb-4 text-center">My Profile</h2>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Profile Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="https://via.placeholder.com/150" class="rounded-circle img-thumbnail mb-3" alt="Profile Picture">
                        <h5>{{ $name }}</h5>
                        <p class="text-muted">Member Since: {{ $created_at }}</p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row">Name:</th>
                                    <td>{{ $name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email:</th>
                                    <td>{{ $email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Total Orders:</th>
                                    <td>{{ $order_count }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Wishlist Items:</th>
                                    <td>{{ $wishlist_count }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <a wire:navigate href="/my-orders" >
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Orders</h5>
                            <p class="card-text display-4 text-primary">{{ $order_count }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a wire:navigate href="/wishlist" >
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">Wishlist</h5>
                            <p class="card-text display-4 text-success">{{ $wishlist_count }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>
