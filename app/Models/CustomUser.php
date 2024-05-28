<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Needed for token authentication
use Laravel\Sanctum\HasApiTokens; // Trait for API token authentication
use Illuminate\Support\Str; // Provides string helper functions

class CustomUser extends Authenticatable
{
    use HasFactory, HasApiTokens;

    /**
     * The "booted" method of the model.
     * Registers an event listener for the "creating" event.
     * Generates and sets a unique user ID before creating a new instance.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->setAttribute('user_Id', self::generateUniqueId());
        });
    }

    /**
     * Generate a unique ID with a prefix and suffix.
     *
     * @return string
     */
    protected static function generateUniqueId()
    {
        $prefix = 'USER_';
        $suffix = '_' . Str::random(5);
        $id = $prefix . Str::uuid()->toString() . $suffix;

        // Check if the ID already exists in the database
        if (static::whereId($id)->exists()) {
            return static::generateUniqueId(); // Recursively generate a new ID if it already exists
        }

        return $id;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'mobile',
    //     'password',
    //     'nid',
    //     'address',
    //     'thana',
    //     'zilla',
    //     'district',
    //     'division',
    //     'subscription',
    // ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    // protected $attributes = [
    //     'thana' => 'not selected',
    //     'zilla' => 'not selected',
    //     'district' => 'not selected',
    //     'division' => 'not selected',
    //     'image' => 'not uploaded',
    //     'subscription' => 'no package'
    // ];

    /**
     * The attributes that should be cast to native types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
