<?php

/**
 * @package app\FooterContent
 *
 * @class FooterContent
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeaderContent extends Model
{
    protected $table = 'setting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'type', 'content',
    ];
}
