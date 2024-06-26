<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;//needed for token
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $table = 'tbl_admins';
    //Fields that will be filled
    protected $fillable = ['email', 'password', 'role', 'status',];
    //Fields that will be filled default but values will not be null
    protected $attributes = ['status' => 'active', 'role' => 'sub',];
}
