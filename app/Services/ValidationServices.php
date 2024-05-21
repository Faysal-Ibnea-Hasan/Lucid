<?php

namespace App\Services;
use Illuminate\Support\Facades\Validator;

class ValidationServices {
    public static function admin_validation(array $data){
        return Validator::make($data,[
            'email'=> 'required|email',
            'password'=> 'required',
        ]);
    }
}
