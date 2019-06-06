<?php

/**
 * @package app\Occasion
 *
 * @class Occasion
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

namespace App;

use Validator;
use Illuminate\Database\Eloquent\Model;

class Occasion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'artist_profile_id', 'title', 'price'
    ];

    public function validator($data)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'title' => 'required|min:2|max:30|regex:/^[\pL\s\-]+$/u',
            'price' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'artist_profile_id' => 'required|regex:/^\d*(\.\d{1,2})?$/',
        ], $messages
        );
        return $validator;
    }
}
