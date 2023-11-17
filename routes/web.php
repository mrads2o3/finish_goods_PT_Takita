<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrdersItemsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function(){
    return redirect()->route('login');
});

Route::group(['middleware' => 'guest'], function(){
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/items', [ItemsController::class, 'items'])->name('items');
    Route::post('/items', [ItemsController::class, 'update'])->name('items.update');
    Route::post('/items/add', [ItemsController::class, 'add'])->name('items.add');
    Route::post('/items/delete/{id}', [ItemsController::class, 'delete'])->name('items.delete');
    
    Route::get('/customers', [CustomerController::class, 'customer'])->name('customers');
    Route::post('/customers', [CustomerController::class, 'update'])->name('customers.update');
    Route::post('/customers/add', [CustomerController::class, 'add'])->name('customers.add');
    Route::post('/customers/delete/{id}', [CustomerController::class, 'delete'])->name('customers.delete');
    
    Route::get('/transactions', [TransactionsController::class, 'transaction'])->name('transactions');
    Route::post('/transactions', [TransactionsController::class, 'update'])->name('transactions.update');
    Route::post('/transactions/add', [TransactionsController::class, 'add'])->name('transactions.add');
    Route::post('/transactions/delete/{id}', [TransactionsController::class, 'delete'])->name('transactions.delete');
    
    Route::get('/orders', [OrdersController::class, 'orders'])->name('orders');
    Route::post('/orders/add', [OrdersController::class, 'orderAdd'])->name('order.add');
    Route::get('/orders/cancel/{id}', [OrdersController::class, 'orderCancel'])->name('order.cancel');
    Route::get('/orders/send/{id}', [OrdersController::class, 'orderSending'])->name('order.sending');
    Route::get('/orders/complete/{id}', [OrdersController::class, 'orderComplete'])->name('order.complete');
    Route::post('/orders/add_order_item', [OrdersController::class, 'ordersAddItem'])->name('orders.add_order_item');
    Route::post('/orders/items/delete/{id}', [OrdersController::class, 'ordersDelItem'])->name('orders.del_item');
    Route::get('/orders/{id}', [OrdersController::class, 'ordersDetails'])->name('orders.details');
});