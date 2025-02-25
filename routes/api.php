<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\GroupSubjectController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\RoleUserController;
use App\Http\Controllers\API\SubjectTeacherController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);
    Route::prefix('subjects')->group(function () {
        ROute::get('/', [SubjectController::class, 'index']);
        Route::get('/{subject}', [SubjectController::class, 'show']);
        Route::post('/', [SubjectController::class, 'store']);
        Route::put('/{subject}', [SubjectController::class, 'update']);
        Route::delete('/{subject}', [SubjectController::class, 'destroy']);
    });
    Route::prefix('rooms')->group(function () {
        ROute::get('/', [RoomController::class, 'index']);
        Route::get('/',[RoomController::class, 'show']);
        Route::post('/', [RoomController::class, 'store']);
        Route::put('/{room}', [RoomController::class, 'update']);
        Route::delete('/{room}', [RoomController::class, 'destroy']);
    });
    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index']);
        Route::get('/{group}',[GroupController::class, 'show']);
        Route::post('/', [GroupController::class, 'store']);
        Route::put('/{group}', [GroupController::class, 'update']);
        Route::delete('/{group}', [GroupController::class, 'destroy']);
    });
    Route::resource('teacher-subjects', SubjectTeacherController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('group-subjects', GroupSubjectController::class);
    Route::resource('role-users', RoleUserController::class);
});
