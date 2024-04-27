<?php

\Stellar\Vortex\Route::get('/test2', function () {
    dd('aaa');
})->name('cleber')->prefix('web');