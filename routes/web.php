<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Mail\UserCreated;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/date', function () {



    $month = Carbon::now()->month;
    $totalAmount = Transaction::where('team_id', 2)->whereMonth('created_at', $month)->sum('amount');

    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();
    $totalAmountWeek = Transaction::where('team_id', 2)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('amount');
    dd($totalAmountWeek);
});

Route::get('/test', function () {

    $user = Auth::user();


    Gate::authorize('isOwner', $user);
    return 'Done';
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::resource('sales', SaleController::class);
    Route::get('sales/receipts/{receipt}', [SaleController::class, 'receipt'])->name('sales.receipt');
    //one way
    // Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('can:edit-product,product');
    //another way
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->can('edit-product', 'product');
    Route::get('/instocks', function () {
        return view('instocks');
    })->name('instocks');
});
