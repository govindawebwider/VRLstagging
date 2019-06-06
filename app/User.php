<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'full_name', 'user_name', 'email', 'password', 'remember_token', 'profile_id', 'is_account_active',
        'is_email_active', 'type', 'gender', 'phone_no', 'date_of_birth', 'device_token', 'device_type', 'access_token'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function is_email_active($email)
    {
        $result = DB::table('users')->where('email', '=', $email)->first();
        if (count($result) > 0) {

            return $result->is_email_active;
        }
    }

    public static function is_account_active($email)
    {
        $result = DB::table('users')->where('email', '=', $email)->first();
        if (count($result) > 0) {
            return $result->is_account_active;
        }
    }

    /**
     * validates the array.
     *
     * @param  array $data
     * @return array
     */
    public function validator($data)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'new_password.regex' => ' Use at least one letter, one numeral & one special character',
            'password.regex' => ' Use at least one letter, one numeral & one special character',
            'password.min' => ' Old password should be at least 8 characters',
            'phone_no.min' => ' Phone no. should be at least 10 characters',
            'phone_no.max' => ' Phone no. should be less than 15 characters',
            'email.unique' => ' Email address already taken',
            'user_name.unique' => ' User name already taken',
            'user_name.regex' => ' User letters, numbers and underscore only',
            'date_of_birth.date_format' => 'Date format must be in m-d-Y (eg. 05-20-2018)',
            /*'gender.in' => ' Gender should be Male or Female',*/
        ];
        return Validator::make($data, [
            'full_name' => 'required',
            'user_name' => 'required|regex:/^[A-Za-z0-9_]+$/|unique:users,user_name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
            'phone_no' => 'required|min:10|max:15',
            /*'gender' => 'in:Male,Female',*/
            'date_of_birth' => 'date_format:"m-d-Y"',
            'country' => 'required',
            'device_token' => 'required',
            'device_type' => 'required',
            'image' => 'mimes:jpeg,jpg,png'
        ], $messages);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne('App\Profile', 'ProfileId', 'profile_id');
    }
}
