<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/user22323", function (Request $request) {
    return $request->user();
})->middleware("auth:sanctum");
