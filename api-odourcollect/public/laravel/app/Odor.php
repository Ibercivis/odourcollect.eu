<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Odor extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_odor_type', 'id_user', 'verified', 'track', 'status', 'name', 'slug', 'description', 'origin', 'color', 'id_odor_intensity', 'id_odor_duration', 'id_odor_annoy', 'published_at'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatable  = [
       'name', 'slug', 'description'
    ];

    /* RELACIONS 1-1 */
    public function location()
    {
        return $this->hasOne('App\Location', 'id_odor', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'id_user');
    }

    /* RELACIONS 1-N */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'id_odor', 'id')->where('hidden', '0');
    }

    public function likes()
    {
        return $this->hasMany('App\Like', 'id_odor', 'id');
    }

    public function tracks()
    {
        return $this->hasMany('App\OdorTrack', 'id_odor', 'id');
    }

    /* RELACIONS N-M */
    public function zones()
    {
        return $this->belongsToMany('App\Zone', 'odor_zones', 'id_odor', 'id_zone')->withPivot('verified')->withTimestamps();
    }

    /** FILTERS **/
    public function scopeCreator($query, $user){
        if($user != ''){
            $query->where('id_user', $user);
        }
    }

    public function scopeName($query, $name){
        if($name != ''){
            $query->where('name', 'LIKE', '%'.$name.'%');
        }
    }

    /* public function scopeType($query, $id_odor_type){
        if($id_odor_type != ''){
            $query->where('id_odor_type', $id_odor_type);
        }
    } */ 

    public function scopeType($query, $odors_ids){
        if(!empty($odors_ids)){
            $query->whereIn('id_odor_type', $odors_ids);
        }
    }

    public function scopeZone($query, $id_zone)
    {
        if(!empty($id_zone)){
            return $query->whereHas('zones', function ($query) use ($id_zone) {
                $query->where('zones.id', $id_zone);
            });
        }
    }

    public function scopeIntensity($query, $id_odor_intensity){
        if($id_odor_intensity != ''){
            $query->where('id_odor_intensity', $id_odor_intensity);
        }
    }

    public function scopeAnnoy($query, $id_odor_annoy){
        if($id_odor_annoy != ''){
            $query->where('id_odor_annoy', $id_odor_annoy);
        }
    }

    public function scopeDescription($query, $description){
        if($description != ''){
            $query->where('description', 'LIKE', '%'.$description.'%');
        }
    }

    public function scopeVerified($query, $verified){
        if ($verified != ''){
            $query->where('verified', $verified);
        }
    }

    public function scopePublished($query, $init, $end){
        if ($init != ''){
            $query->whereBetween('published_at', array($init, $end));
        }
    }

    public function scopeStatus($query, $status){
        if ($status != ''){
            $query->where('status', $status);
        }
    }

}
