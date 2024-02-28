<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
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

Route::get('/', [AuthenticatedSessionController::class,'create']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/reports', [ReportController::class, 'edit'])->name('reports');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/view/{id}', [CustomerController::class, 'viewcustomer'])->name('customers.view');


    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices');
    Route::get('/invoices/view/{id}', [InvoiceController::class, 'viewinvoice'])->name('invoices.view');
    Route::get('/invoices/edit/{id}', [InvoiceController::class, 'editinvoice'])->name('invoices.edit');
    Route::post('/invoices/edit/{id}', [InvoiceController::class, 'editinvoicestore']);

});

//routes for admin
Route::group(['middleware' => ['role:admin']], function(){

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/crb', [ReportController::class, 'crbindex'])->name('reports.crb');
    Route::get('/reports/crb/{id}', [ReportController::class, 'crbviewgroup'])->name('reports.crb.group');


    Route::get('/users', [UserController::class, 'index'])->name('users');

    Route::get('/users/resetpassword/{id}', [UserController::class, 'resetpassword'])->name('users.resetpassword');
    Route::post('/users/resetpassword/{id}', [UserController::class, 'resetpasswordstore']);


    Route::get('/users/new', [UserController::class, 'newuser'])->name('users.new');
    Route::post('/users/new', [UserController::class, 'newuserstore']);


    Route::get('/users/view/{id}', [UserController::class, 'viewuser'])->name('users.view');

    Route::get('/users/edit/{id}', [UserController::class, 'edituser'])->name('users.edit');
    Route::post('/users/edit/{id}', [UserController::class, 'edituserstore']);

    Route::get('/users/block/{id}', [UserController::class, 'blockuser'])->name('users.block');
    Route::post('/users/block/{id}', [UserController::class, 'blockuserstore']);

    Route::get('/users/activate/{id}', [UserController::class, 'activateuser'])->name('users.activate');
    Route::post('/users/activate/{id}', [UserController::class, 'activateuserstore']);



    // routes for customer management

    

    Route::get('/customers/new', [CustomerController::class, 'newcustomer'])->name('customers.new');
    Route::post('/customers/new', [CustomerController::class, 'newcustomerstore']);



    Route::get('/customers/edit/{id}', [CustomerController::class, 'editcustomer'])->name('customers.edit');
    Route::post('/customers/edit/{id}', [CustomerController::class, 'editcustomerstore']);

//invoices routes


Route::get('/invoices/new', [InvoiceController::class, 'newinvoice'])->name('invoices.new');
Route::post('/invoices/new', [InvoiceController::class, 'newinvoicestore']);




Route::get('/get-customers/{userId}',  [InvoiceController::class, 'getCustomersByUserId']);


});
require __DIR__.'/auth.php';
