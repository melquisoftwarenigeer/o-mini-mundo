<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;


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


Route::view('/', 'auth.login')->name('login');

Route::post('/register', [AuthController::class, 'store']);

Route::get('/autentic', [AuthController::class, 'login'])->name('autentic');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/store-token', function (Request $request) {
    session(['token' => $request->input('token')]);
    return response()->json(['status' => 'ok']);
});

Route::view('/dashboard', 'dashboard')->middleware('jwt.web')->name('dashboard');

Route::middleware('jwt.web')->group(function () {
    Route::view('/projects', 'projects.index')->name('projects.index');
    Route::view('/projects/create', 'projects.create')->name('projects.create');
    Route::view('/projects/{id}/edit', 'projects.edit')->name('projects.edit');
    // Route::view('/projects/{id}', 'projects.show')->name('projects.show');
    Route::get('/projects/{id}', function ($id) {
        $project = Project::with(['tasks.predecessor'])->findOrFail($id);
        return view('projects.show', compact('project'));});    
});

Route::middleware('jwt.web')->group(function () {
    Route::view('/projects/{projectId}/tasks', 'tasks.index')->name('tasks.index');
    Route::view('/projects/{projectId}/tasks/create', 'tasks.create')->name('tasks.create');
    Route::get('/projects/{projectId}/tasks/{taskId}/edit', [TaskController::class, 'edit'])->name('tasks.edit');    


    Route::get('/tasks/{taskId}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::get('/tasks', [TaskController::class, 'indexAll'])->name('tasks.indexall');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

});
