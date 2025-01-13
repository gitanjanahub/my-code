<div>
    <div class="container py-5">
        <section class="row justify-content-center">
            <div class="col-lg-8 bg-white border rounded p-4 text-center">
                <h1 class="text-danger fw-bold mb-3">Payment Failed! Order Cancelled!</h1>
                <p class="text-muted mb-4">Unfortunately, your payment could not be processed, and your order has been canceled. Please try again or contact our support team for assistance.</p>
                <div class="my-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" fill="currentColor" class="text-danger bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zM4.354 4.354a.5.5 0 1 1 .708-.708L8 6.293l2.938-2.939a.5.5 0 0 1 .707.708L8.707 7l2.939 2.938a.5.5 0 0 1-.707.707L8 7.707l-2.938 2.939a.5.5 0 1 1-.708-.707L7.293 7 4.354 4.354z"/>
                    </svg>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a wire:navigate href="/products" class="btn btn-outline-secondary">Go Back to Shop</a>
                    <a wire:navigate href="/contact-support" class="btn btn-danger">Contact Support</a>
                </div>
            </div>
        </section>
    </div>
</div>
