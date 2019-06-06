<?php

namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $table = 'admins';

     protected  $primarykey = 'id';

     protected $fillable = [
     'name', 'email', 'password',
     ];
     
     // protected $guard = "admins";
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    'password', 'remember_token',
    ];
}
