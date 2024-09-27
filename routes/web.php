<?php

use App\Http\Controllers\ProductController;
use App\Mail\UserCreated;
use App\Models\Team;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {

    $ownTeams = Team::where('user_id', Auth::user()->id)->get();
    $currentTeam = Auth::user()->currentTeam;
    dd(Auth::user()->currentTeam);
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
    //one way
    // Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('can:edit-product,product');
    //another way
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->can('edit-product', 'product');
    Route::get('/instocks', function () {
        return view('instocks');
    })->name('instocks');
});
