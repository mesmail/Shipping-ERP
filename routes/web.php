<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', fn() => redirect()->route('tracking'));
Route::get('/track', [TrackingController::class, 'index'])->name('tracking');
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Auth
require __DIR__ . '/auth.php';

// Protected
Route::middleware(['auth', \App\Http\Middleware\SetLocale::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Shipments
    Route::resource('shipments', ShipmentController::class);
    Route::patch('/shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
    Route::get('/shipments/{shipment}/label', [ShipmentController::class, 'label'])->name('shipments.label');
    Route::get('/shipments/{shipment}/label/pdf', [ShipmentController::class, 'labelPdf'])->name('shipments.label.pdf');
    Route::get('/shipments/{shipment}/barcode', [ShipmentController::class, 'barcode'])->name('shipments.barcode');
    Route::get('/shipments/{shipment}/qrcode', [ShipmentController::class, 'qrcode'])->name('shipments.qrcode');

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Branches
    Route::resource('branches', BranchController::class)->except(['show']);

    // Drivers
    Route::resource('drivers', DriverController::class)->except(['show']);

    // Users (admin only)
    Route::resource('users', UserController::class)->middleware('role:admin')->except(['show']);
});

// Ajax API
Route::middleware(['auth', \App\Http\Middleware\SetLocale::class])->prefix('api')->group(function () {
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('api.customers.search');
});
