<?php

use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\ContactusPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderDetailsPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyProfile;
use App\Livewire\ProductDetailsPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use App\Livewire\WishListPage;
//use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',HomePage::class);
Route::get('/categories',CategoriesPage::class);
Route::get('/products',ProductsPage::class);
Route::get('/cart',CartPage::class);
Route::get('/products/{slug}',ProductDetailsPage::class);
Route::get('/wishlist',WishListPage::class);
Route::get('/contac-tus',ContactusPage::class);


//Route::get('/checkout',CheckoutPage::class);

Route::middleware('guest')->group(function (){

    Route::get('/login',LoginPage::class)->name('login');
    Route::get('/register',RegisterPage::class);
    Route::get('/forgot-password',ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset{token}',ResetPasswordPage::class)->name('password.reset');//modified the code {token}while integrtn

});

Route::middleware('auth')->group(function (){

    Route::get('/logout', function (){
      auth()->logout();
      return redirect('/');
    });

    Route::get('/checkout',CheckoutPage::class);
    Route::get('/my-orders',MyOrdersPage::class);
    Route::get('/my-orders/{order_id}',MyOrderDetailsPage::class)->name('my-orders.show');//named at the time of email send started
    Route::get('/success',SuccessPage::class)->name('success');
    Route::get('/cancel',CancelPage::class)->name('cancel');
    Route::get('/my-profile',MyProfile::class);

});

// Utility Route to Clear Cart
// Route::get('/clear-cart', function () {
//     Cookie::queue(Cookie::forget('cart_items'));
//     return response()->json([
//         'message' => 'Cart cookie cleared successfully!',
//     ]);
// });
