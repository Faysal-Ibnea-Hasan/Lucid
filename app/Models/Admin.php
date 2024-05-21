<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $table = 'tbl_admins';
    //Fields that will be filled
    protected $fillable = ['email', 'password', 'role', 'status'];
    //Fields that will be filled default but values will not be null
    protected $attributes = ['status' => 'active','role' => 'sub'];
}
