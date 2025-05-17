<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;


class CryptoController extends Controller {
    public function cryptography(Request $request) {

        $data = $request->data??"Welcome to Cryptography";
        $action = $request->action??"Encrypt";
        $result = $request->result??"";
        $status = "Failed";


            if($request->action=="Encrypt") {
                $temp = openssl_encrypt($request->data, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
                if($temp) {
                    $status = 'Encrypted Successfully';
                    $result = base64_encode($temp);
        }
    }


            else if($request->action=="Decrypt") {
                $temp = base64_decode($request->data);
                $result = openssl_decrypt($temp, 'aes-128-ecb',  'thisisasecretkey', OPENSSL_RAW_DATA, '');
                if($result) $status = 'Decrypted Successfully';
        }


            else if($request->action=="Hash") {
                $temp = hash('sha256', $request->data);
                $result = base64_encode($temp);
                $status = 'Hashed Successfully';
            }


            else if($request->action=="Sign") {
                $path = storage_path('app/private/useremail1@domain.com.pfx');
                $password = '1234';
                $certificates = [];
                $pfx = file_get_contents($path);
                openssl_pkcs12_read($pfx, $certificates, $password);
                $privateKey = $certificates['pkey'];
                $signature = '';
                if(openssl_sign($request->data, $signature, $privateKey, 'sha256')) {
                    $result = base64_encode($signature);
                    $status = 'Signed Successfully';
                }
            }

        return view('crypto.cryptography', compact('data', 'result', 'action', 'status'));

    }
}
