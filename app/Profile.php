<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
  //  protected $table = 'profile';
    //protected odbc_primarykeys(connection_id, qualifier, owner, table)
	protected $primaryKey = 'ProfileId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'Name', 'EmailId', 'Type', 'Gender', 'DateOfBirth', 'VideoPrice', 'timestamp', 'profile_url', 'profile_path',
        'BannerImg', 'header_image', 'video_background', 'State', 'country', 'MobileNo',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\User', 'profile_id', 'ProfileId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function artistCategory()
    {
        return $this->hasMany('App\ArtistCategory');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testimonial()
    {
        return $this->hasMany('App\Testimonial', 'to_profile_id', 'ProfileId');
    }
}


