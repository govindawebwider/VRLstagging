<?php

/**
 * @package app\ReactionVideo
 *
 * @class ReactionVideo
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReactionVideo extends Model
{
    protected $table = 'reactionvideos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'UserId', 'VideoUploadDate', 'VideoName', 'VideoFormat', 'VideoURL', 'thumbnail', 'status',
    ];
}
