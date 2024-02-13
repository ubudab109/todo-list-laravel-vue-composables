<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('', function() {
    if (file_exists(public_path('/storage/files/1698090763089869900.png'))) {
        unlink(public_path('/storage/files/1698090763089869900.png'));
        return response()->json(['ada' => 'ya']);
    } else {
        return response()->json(['ada' => 'ga']);
    }
    // return public_path('storage/files/1698090763089869900.png');
    // return unlink('http://localhost:8000/storage/files/1698082369021583400.png');
});
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum:users')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('files', FileController::class);
    Route::resource('tasks', TaskController::class);
    Route::put('task-completed/{task}', [TaskController::class, 'markComplete']);
    Route::put('task-archived/{task}', [TaskController::class, 'markArchived']);
});
// Route::group(['middleware' => ['auth:users']], function () {
//     // Route::resource('files', FileController::class);
// });
