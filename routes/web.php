<?php

use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\MedicationsController;
use App\Http\Controllers\MonthlySalesController;
use App\Http\Controllers\PrescriptionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\SalesDetailsController;
use App\Http\Controllers\SalesStatisticsController;
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

Route::get('/', function () {
    return redirect()->route('patients.index');
});


Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::resource('patients', PatientsController::class);
Route::get('/medications/export', [MedicationsController::class, 'export'])->name('medications.export');
Route::resource('medications', MedicationsController::class);
Route::resource('invoices', InvoicesController::class);
Route::resource('prescriptions', PrescriptionsController::class);
Route::get('/sales-statistics', [SalesStatisticsController::class, 'index'])->name('sale.statistics.index');
Route::get('/sales-details', [SalesDetailsController::class, 'index'])->name('sale.details.index');
Route::get('/invoices/{id}/print', [InvoicesController::class, 'printInvoice'])->name('invoices.print');

Route::get('/reports/monthly-sales', [MonthlySalesController::class, 'index'])->name('reports.monthly_sales');

require __DIR__ . '/auth.php';
