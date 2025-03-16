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
    return view('bill',compact("bill")); 
});
Route::get('/transcript', function () {
    $courses = [
        ['name' => 'Operating Systems', 'grade' => 'A'],
        ['name' => 'Computer Networks', 'grade' => 'B+'],
        ['name' => 'Cybersecurity', 'grade' => 'A-'],
        ['name' => 'Database Systems', 'grade' => 'B'],
        ['name' => 'Web Development', 'grade' => 'A']
    ];

    // Function to convert letter grades to GPA points
    function gradeToGpa($grade) {
        $gradeScale = [
            'A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0,
            'B-' => 2.7, 'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
            'D+' => 1.3, 'D' => 1.0, 'F' => 0.0
        ];
        return $gradeScale[$grade] ?? 0.0;
    }

    // Calculate total GPA
    $totalPoints = 0;
    foreach ($courses as $course) {
        $totalPoints += gradeToGpa($course['grade']);
    }
    $gpa = count($courses) > 0 ? round($totalPoints / count($courses), 2) : 0;

    return view('transcript', compact('courses', 'gpa'));
});


use App\Http\Controllers\Web\ProductsController;

Route::get('products', [ProductsController::class, 'list']);



use App\Http\Controllers\Web\UsersController;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
