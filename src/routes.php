<?php

use Illuminate\Support\Facades\Route;
use Kwidoo\RemoteUser\Http\RemoteUserController;

Route::get('/sanctum/token', RemoteUserController::class . '@token');
