<?php

/**
 * @package app\ArtistCategory
 *
 * @class ArtistCategory
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtistCategory extends Model
{
    protected $table = 'artist_category';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'profile_id', 'category_id', 'main_category'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Category', 'main_category', 'id');
    }
}
