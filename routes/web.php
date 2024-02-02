<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlanningController;

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
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // Route Products
    Route::get('/tasks/export', [TaskController::class, 'export'])->name('tasks.export');
    Route::get('/tasks/import', [TaskController::class, 'import'])->name('tasks.import');
    Route::post('/tasks/import', [TaskController::class, 'handleImport'])->name('tasks.handleImport');
    Route::resource('/tasks', TaskController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/planning', PlanningController::class);
});
require __DIR__.'/auth.php';