<?php

use App\Http\Controllers\CsvController;
use Illuminate\Support\Facades\Route;


Route::post('/',[CsvController::class,'checkDifference']);
