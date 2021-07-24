<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvImportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [CsvImportController::class, 'getImport'])->name('import');
Route::post('/import_parse', [CsvImportController::class, 'parseImport'])->name('import_parse');
Route::post('/import_process', [CsvImportController::class, 'processImport'])->name('import_process');
