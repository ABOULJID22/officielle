<?php

use Illuminate\Support\Facades\Route;

Route::get('/_api_test_clean', function () {
    return response()->json(['ok' => true]);
});
