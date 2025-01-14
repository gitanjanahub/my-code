<div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Contact Us</h4>
                    </div>
                    <div class="card-body">
                        @if ($contactDetail)
                            <div class="mb-3">
                                <h5 class="card-title font-weight-bold">Company Name:</h5>
                                <p>{{ $contactDetail->name }}</p>
                            </div>
                            <div class="mb-3">
                                <h5 class="card-title font-weight-bold">Address:</h5>
                                <p>{{ $contactDetail->address }}</p>
                            </div>
                            <div class="mb-3">
                                <h5 class="card-title font-weight-bold">Email:</h5>
                                <p>{{ $contactDetail->email }}</p>
                            </div>
                            <div class="mb-3">
                                <h5 class="card-title font-weight-bold">Phone:</h5>
                                <p>{{ $contactDetail->phone }}</p>
                            </div>
                            @if ($contactDetail->logo)
                                <div class="mb-3 text-center">
                                    <h5 class="card-title font-weight-bold">Company Logo:</h5>
                                    <img src="{{ asset('storage/' . $contactDetail->logo) }}" alt="Company Logo" class="img-fluid" style="max-width: 200px;">
                                </div>
                            @endif
                        @else
                            <p class="text-danger">Company details are not available at the moment.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
