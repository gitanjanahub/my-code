<?php

use App\Livewire\Admin\AttributeCreate;
use App\Livewire\Admin\AttributeEdit;
use App\Livewire\Admin\Attributes;
use App\Livewire\Admin\AttributeView;
use App\Livewire\Admin\BannerCreate;
use App\Livewire\Admin\BannerEdit;
use App\Livewire\Admin\Banners;
use App\Livewire\Admin\BannerView;
use App\Livewire\Admin\BrandCreate;
use App\Livewire\Admin\BrandEdit;
use App\Livewire\Admin\Brands;
use App\Livewire\Admin\BrandView;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\CategoryCreate;
use App\Livewire\Admin\CategoryEdit;
use App\Livewire\Admin\CategoryView;
use App\Livewire\Admin\ChangePassword;
use App\Livewire\Admin\Contactus;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\StaffDashboard;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\OrderCreate;
use App\Livewire\Admin\OrderEdit;
use App\Livewire\Admin\Orders;
use App\Livewire\Admin\OrderView;
use App\Livewire\Admin\ProductCreate;
use App\Livewire\Admin\ProductEdit;
use App\Livewire\Admin\Products;
use App\Livewire\Admin\ProductView;
use App\Livewire\Admin\StockManagementCreate;
use App\Livewire\Admin\StockManagementEdit;
use App\Livewire\Admin\StockManagements;
use App\Livewire\Admin\StockManagementView;
use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\UserEdit;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\UserView;
use App\Livewire\Admin\ContactDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public route for admin login
Route::get('admin/login', Login::class)->name('admin.login');

// Protected routes for admin and staff
Route::middleware('admin')->group(function () {

    Route::get('admin/dashboard', Dashboard::class)->name('admin.dashboard');

    Route::get('admin/categories', Categories::class)->name('admin.categories');
    Route::get('admin/categories/create', CategoryCreate::class)->name('admin.category-create');
    Route::get('/admin/category/edit/{id}', CategoryEdit::class)->name('admin.category-edit');
    Route::get('/admin/category/{categoryId}', CategoryView::class)->name('admin.category-view');

    Route::get('admin/brands', Brands::class)->name('admin.brands');
    Route::get('admin/brands/create', BrandCreate::class)->name('admin.brand-create');
    Route::get('/admin/brands/edit/{id}', BrandEdit::class)->name('admin.brand-edit');
    Route::get('/admin/brands/{brandId}', BrandView::class)->name('admin.brand-view');

    Route::get('admin/attributes', Attributes::class)->name('admin.attributes');
    Route::get('admin/attributes/create', AttributeCreate::class)->name('admin.attribute-create');
    Route::get('/admin/attributes/edit/{id}', AttributeEdit::class)->name('admin.attribute-edit');
    Route::get('/admin/attributes/{attributeId}', AttributeView::class)->name('admin.attribute-view');

    Route::get('admin/products', Products::class)->name('admin.products');
    Route::get('admin/products/create', ProductCreate::class)->name('admin.product-create');
    Route::get('/admin/products/edit/{id}', ProductEdit::class)->name('admin.product-edit');
    Route::get('/admin/products/{productId}', ProductView::class)->name('admin.product-view');

    Route::get('admin/stocks', StockManagements::class)->name('admin.stocks');
    Route::get('admin/stocks/create/{product}', StockManagementCreate::class)->name('admin.stock-create');
    Route::get('/admin/stocks/edit/{id}', StockManagementEdit::class)->name('admin.stock-edit');
    Route::get('/admin/stocks/view/{productId}', StockManagementView::class)->name('admin.stock-view');

    Route::get('admin/orders', Orders::class)->name('admin.orders');
    Route::get('admin/orders/create', OrderCreate::class)->name('admin.order-create');
    Route::get('/admin/orders/edit/{id}', OrderEdit::class)->name('admin.order-edit');
    Route::get('/admin/orders/{orderId}', OrderView::class)->name('admin.order-view');

    Route::get('admin/users', Users::class)->name('admin.users');
    Route::get('admin/users/create', UserCreate::class)->name('admin.user-create');
    Route::get('/admin/users/edit/{id}', UserEdit::class)->name('admin.user-edit');
    Route::get('/admin/users/{userId}', UserView::class)->name('admin.user-view');

    Route::get('admin/banners', Banners::class)->name('admin.banners');
    Route::get('admin/banners/create', BannerCreate::class)->name('admin.banner-create');
    Route::get('/admin/banners/edit/{id}', BannerEdit::class)->name('admin.banner-edit');
    Route::get('/admin/banners/{bannerId}', BannerView::class)->name('admin.banner-view');

    Route::get('/admin/change-password', ChangePassword::class)->name('admin.change-password');
    Route::get('admin/contact-details', ContactDetails::class)->name('admin.contact-details');


    Route::get('admin/logout', function () {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    })->name('admin.logout');
});
