<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/signup', 'App\Http\Controllers\AuthController@signup');  
Route::post('/login', 'App\Http\Controllers\AuthController@login');    


Route::middleware('auth.jwt')->group(function () {
    Route::prefix('clientes')->group(function () {
        Route::get('/', 'App\Http\Controllers\ClientController@index');       
        Route::post('/', 'App\Http\Controllers\ClientController@store');      
        Route::get('/{cliente}', 'App\Http\Controllers\ClientController@show');
        Route::put('/{cliente}', 'App\Http\Controllers\ClientController@update'); 
        Route::delete('/{cliente}', 'App\Http\Controllers\ClientController@delete'); 
    });

    Route::prefix('produtos')->group(function () {
        Route::get('/', 'App\Http\Controllers\ProductController@index');     
        Route::post('/', 'App\Http\Controllers\ProductController@store');     
        Route::get('/{produto}', 'App\Http\Controllers\ProductController@show'); 
        Route::put('/{produto}', 'App\Http\Controllers\ProductController@update'); 
        Route::delete('/{produto}', 'App\Http\Controllers\ProductController@destroy'); 
    });

    Route::prefix('vendas')->group(function () {
        Route::post('/', 'App\Http\Controllers\SaleController@store');    
    });
});
