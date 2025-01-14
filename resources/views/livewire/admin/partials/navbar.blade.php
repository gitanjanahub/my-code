<div>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="" target="_blank" class="brand-link">
            <img src="{{ asset('assets/images/RonaldCodesLogo.png') }}" alt="RonaldCodes Logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">

            {{-- <a href="https://www.youtube.com/@RonaldCodes23" target="_blank"><img
                    src="{{ asset('assets/images/RonaldCodesLogo.png') }}" alt="Ronald Codes Logo"
                    class="brand-image img-circle elevation-3 rounded-circle cursor-pointer" style="width: 50px" />
            </a> --}}

            <span class="brand-text font-weight-light">Ecommerce</span>
        </a>

        <div class="sidebar">
            {{-- Sidebar user panel (optional)  --}}
            {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('assets/bms_logo.png') }}" class="img-circle elevation-2" alt="User Image">
                </div>

                <div class="info">
                    <a href="#" class="d-block">Alexander Pierce</a>
                </div>
            </div> --}}

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>


                    <!-- Banners -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.banners') }}"
                           class="nav-link {{ request()->is('admin/banners*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-image"></i>
                            <p>Banners</p>
                        </a>
                    </li>

                    <!-- Categories -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.categories') }}"
                           class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Categories</p>
                        </a>
                    </li>

                    <!-- Brands -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.brands') }}"
                           class="nav-link {{ request()->is('admin/brands*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-book"></i>
                            <p>Brands</p>
                        </a>
                    </li>

                    <!-- Attributes -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.attributes') }}"
                           class="nav-link {{ request()->is('admin/attributes*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-mars-double"></i>
                            <p>Attributes</p>
                        </a>
                    </li>

                    <!-- Products -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.products') }}"
                           class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-cart-plus"></i>
                            <p>Products</p>
                        </a>
                    </li>

                    <!-- Stock Management -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.stocks') }}"
                           class="nav-link {{ request()->is('admin/stocks*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-book"></i>
                            <p>Stock Management</p>
                        </a>
                    </li>

                    <!-- Orders -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.orders') }}"
                           class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-briefcase"></i>
                            <p>Orders</p>
                        </a>
                    </li>

                    <!-- Users -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.users') }}"
                           class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>

                    <!-- Contact Us -->
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.contact-details') }}"
                           class="nav-link {{ request()->is('admin/contact-details') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-image"></i>
                            <p>Contact Us</p>
                        </a>
                    </li>
                </ul>
            </nav>


        </div>
    </aside>

</div>
