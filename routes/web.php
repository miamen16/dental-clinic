<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Patients\PatientController;
use App\Http\Controllers\Users\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctors\DoctorController;
use App\Http\Controllers\Appointments\AppointmentController;
use App\Http\Controllers\Services\ServiceController;
use App\Http\Controllers\Receptionists\ReceptionistController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Taxes\TaxController;
use App\Http\Controllers\Invoices\InvoiceController;
use App\Http\Controllers\MedicalRecords\MedicalRecordController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('patients', PatientController::class);
    Route::patch('doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus'])->name('doctors.toggle-status');
    Route::resource('doctors',  DoctorController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    Route::patch('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::resource('receptionists', ReceptionistController::class);
    Route::resource('services', ServiceController::class);
   
    // Must come before the resource, or users/{user} catches "all" as an id
    Route::get('users/all', [UserController::class, 'all'])->name('users.all');
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('taxes', TaxController::class);
    Route::resource('invoices', InvoiceController::class);
});

require __DIR__.'/auth.php';
