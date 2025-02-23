<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); //welcome.blade.php
});
Route::get('/multable/{number?}', function ($number = null) {
    $j = $number??2;
    return view('multable', compact('j')); //multable.blade.php
});
Route::get('/even', function () {
    return view('even'); //even.blade.php
});
Route::get('/prime', function () {
    return view('prime'); //prime.blade.php
});
Route::get('/bill', function () {
    $bill = (object)[];
    $bill->supermarket = "carfour";
    $bill->pos = "#12345";
    $bill->products = [
        (object)["quantity"=>1, "unit"=>"unit","name"=>"twix","price"=>30],
        (object)["quantity"=>1, "unit"=>"unit","name"=>"twix","price"=>30],
        (object)["quantity"=>1, "unit"=>"unit","name"=>"twix","price"=>30],
        (object)["quantity"=>1, "unit"=>"unit","name"=>"twix","price"=>30]
    ];
    $bill->total = 0;
    foreach($bill->products as $product){
        $bill->total += $product->price * $product->quantity;
    }
    $bill->currency = "LE";
    return view('bill',compact("bill")); //prime.blade.php
});