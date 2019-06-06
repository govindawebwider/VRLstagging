<?php

/**
 * @package app\SocialMedia
 *
 * @class SocialMedia
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'social_name', 'social_img', 'social_url', 'addBy_profileId', 'is_active', '_token'
    ];
}
