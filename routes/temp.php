<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/temp/check-users-table', function() {
    $columns = DB::select('SHOW COLUMNS FROM users');
    $indexes = DB::select('SHOW INDEXES FROM users');
    
    return [
        'columns' => $columns,
        'indexes' => $indexes
    ];
});
