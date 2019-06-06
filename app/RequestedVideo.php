<?php

/**
 * @package app\RequestedVideo
 *
 * @class RequestedVideo
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestedVideo extends Model
{


    public $timestamps = false;
    protected $table = 'requested_videos';

    public function send_notification($value = '')

    {

        # code...

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function requestVideo()
    {
        return $this->hasOne('App\Requestvideo', 'VideoReqId', 'request_id');
    }
}

