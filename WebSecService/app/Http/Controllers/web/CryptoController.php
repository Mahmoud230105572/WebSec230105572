<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller; 

class CryptoController extends Controller {
    public function cryptography() {
        return view('crypto.cryptography');
    }
}
