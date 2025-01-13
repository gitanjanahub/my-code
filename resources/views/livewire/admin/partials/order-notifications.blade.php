<div>
    <!-- Notification Bell -->
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        @if ($newOrdersCount > 0)
            <span class="badge badge-warning navbar-badge">{{ $newOrdersCount }}</span>
        @endif
    </a>

    <!-- Dropdown Menu -->
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">{{ $newOrdersCount }} New Orders</span>

        <div class="dropdown-divider"></div>

        @forelse ($newOrders as $order)
            <a wire:navigate href="{{ route('admin.order-view', $order->id) }}" class="dropdown-item">
                <i class="fas fa-shopping-cart mr-2"></i> Order #{{ $order->id }} by {{ $order->user->name ?? 'Guest' }}
                <span class="float-right text-muted text-sm">{{ $order->created_at->diffForHumans() }}</span>
            </a>
        @empty
            <span class="dropdown-item text-muted">No new orders</span>
        @endforelse

        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.orders') }}" class="dropdown-item dropdown-footer">See All Orders</a>
    </div>
</div>
