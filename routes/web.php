<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "response from web.php".now();
});
Route::get('/user', function () {
    return "response from web.php".now();
});
