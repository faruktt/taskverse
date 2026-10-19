<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Buyer\DashboardController as BuyerController;
use App\Http\Controllers\Seller\DashboardController as SellerController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/* ══════════════════════════════════════════
   PUBLIC
══════════════════════════════════════════ */
Route::get('/', fn() => view('welcome'))->name('home');

/* ══════════════════════════════════════════
   AUTH
══════════════════════════════════════════ */
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/* ══════════════════════════════════════════
   BUYER
══════════════════════════════════════════ */
Route::middleware(['auth','App\Http\Middleware\BannedUserMiddleware','App\Http\Middleware\BuyerMiddleware'])
    ->prefix('buyer')->name('buyer.')->group(function () {

    Route::get('/dashboard', [BuyerController::class, 'index'])->name('dashboard');
    Route::get('/browse',    [BuyerController::class, 'browse'])->name('browse');
    Route::get('/gig/{gig}', [BuyerController::class, 'showGig'])->name('gig.show');
    Route::post('/gig/{gig}/order', [BuyerController::class, 'placeOrder'])->name('gig.order');
    Route::get('/orders',           [BuyerController::class, 'orders'])->name('orders');
    Route::get('/order/{order}',    [BuyerController::class, 'showOrder'])->name('order.show');
    Route::post('/order/{order}/complete', [BuyerController::class, 'markComplete'])->name('order.complete');
    Route::post('/order/{order}/revision', [BuyerController::class, 'requestRevision'])->name('order.revision');
    Route::post('/order/{order}/review',   [BuyerController::class, 'storeReview'])->name('order.review');
    Route::get('/profile',              [BuyerController::class, 'profile'])->name('profile');
    Route::post('/profile',             [BuyerController::class, 'updateProfile'])->name('profile.update');
});

/* ══════════════════════════════════════════
   SELLER
══════════════════════════════════════════ */
Route::middleware(['auth','App\Http\Middleware\BannedUserMiddleware','App\Http\Middleware\SellerMiddleware'])
    ->prefix('seller')->name('seller.')->group(function () {

    Route::get('/dashboard', [SellerController::class, 'index'])->name('dashboard');
    Route::get('/gigs',                  [SellerController::class, 'gigs'])->name('gigs');
    Route::get('/gigs/create',           [SellerController::class, 'createGig'])->name('gigs.create');
    Route::post('/gigs',                 [SellerController::class, 'storeGig'])->name('gigs.store');
    Route::get('/gig/{gig}/edit',        [SellerController::class, 'editGig'])->name('gig.edit');
    Route::put('/gig/{gig}',             [SellerController::class, 'updateGig'])->name('gig.update');
    Route::delete('/gig/{gig}',          [SellerController::class, 'deleteGig'])->name('gig.delete');
    Route::get('/orders',                [SellerController::class, 'orders'])->name('orders');
    Route::get('/order/{order}',         [SellerController::class, 'showOrder'])->name('order.show');
    Route::post('/order/{order}/status', [SellerController::class, 'updateOrderStatus'])->name('order.status');
    Route::get('/profile',               [SellerController::class, 'profile'])->name('profile');
    Route::post('/profile',              [SellerController::class, 'updateProfile'])->name('profile.update');
});

/* ══════════════════════════════════════════
   MESSAGES (both buyer & seller)
══════════════════════════════════════════ */
Route::middleware(['auth','App\Http\Middleware\BannedUserMiddleware'])
    ->post('/conversation/{conversation}/message', [MessageController::class, 'send'])
    ->name('message.send');

/* ══════════════════════════════════════════
   ADMIN
══════════════════════════════════════════ */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    Route::middleware('App\Http\Middleware\AdminMiddleware')->group(function () {
        Route::post('/logout',             [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard',           [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users',               [AdminController::class, 'users'])->name('users');
        Route::post('/user/{user}/ban',    [AdminController::class, 'banUser'])->name('user.ban');
        Route::delete('/user/{user}',      [AdminController::class, 'deleteUser'])->name('user.delete');
        Route::get('/gigs',                [AdminController::class, 'gigs'])->name('gigs');
        Route::post('/gig/{gig}/approve',  [AdminController::class, 'approveGig'])->name('gig.approve');
        Route::post('/gig/{gig}/reject',   [AdminController::class, 'rejectGig'])->name('gig.reject');
        Route::delete('/gig/{gig}',        [AdminController::class, 'deleteGig'])->name('gig.delete');
        Route::get('/orders',              [AdminController::class, 'orders'])->name('orders');
        Route::get('/categories',          [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories',         [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/category/{category}', [AdminController::class, 'deleteCategory'])->name('category.delete');
    });
});
