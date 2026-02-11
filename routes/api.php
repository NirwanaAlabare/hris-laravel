<?php

use App\Http\Controllers\Admin\AttendancesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/zk-callback', [AttendancesController::class, 'handleHardwareCallback']);
Route::get('/zk-get-results', [AttendancesController::class, 'getLatestResults']);