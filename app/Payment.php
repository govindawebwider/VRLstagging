<?php

/**
 * @package app\Payment
 *
 * @class Payment
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	
	protected $table = 'payments';

	protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'profile_id', 'video_status', 'stripeTokenType', 'stripeToken', 'customerEmail', 'status', 'videoPrice',
        'payer_id', 'payment_date', 'token', 'charge_id', 'payment_type', 'video_request_id', 'is_refunded',
        'payment_id',
    ];
}



