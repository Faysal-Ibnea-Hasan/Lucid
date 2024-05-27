<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;//needed for token
use Laravel\Sanctum\HasApiTokens;

class CustomUser extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'tbl_users';
    protected $fillable = [
        'user_Id',
        'name',
        'mobile',
        'password',
        'nid',
        'address',
        'thana',
        'zilla',
        'district',
        'division',
        'image',
        'subscription',
    ];
    protected $hidden = ['password'];
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
