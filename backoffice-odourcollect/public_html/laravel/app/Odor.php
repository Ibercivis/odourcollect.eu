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
        'id', 'id_odor_type', 'id_user', 'name', 'slug', 'description', 'origin', 'color', 'id_odor_intensity', 'id_odor_duration', 'id_odor_annoy', 'published_at'
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
        return $this->hasMany('App\Comment', 'id_odor', 'id');
    }

    public function likes()
    {
        return $this->hasMany('App\Like', 'id_odor', 'id');
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

    public function scopeType($query, $odors_ids){
        if($odors_ids != ''){
            $query->leftJoin('odor_types', 'odors.id_odor_type', '=', 'odor_types.id')->leftJoin('odor_parent_types', 'odor_types.id_odor_parent_type', '=', 'odor_parent_types.id')->where('odor_parent_types.id', $odors_ids)->select('odors.*');
        }
    }


    public function scopeSubtype($query, $odors_ids){
        if($odors_ids != ''){
 
            $query->where('id_odor_type', $odors_ids)->select('odors.*');
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

    public function scopeComment($query, $id){
        if (!empty($id)) {
            $query->join('comments', 'comments.id_odor', '=', 'odors.id')->join('users', 'users.id', '=', 'odors.id_user')->where('comments.id_odor', $id)->orderBy('comments.created_at', 'asc');
        }
    }

    public function scopeStatus($query, $value){
        if($value != ''){
            $query->where('verified', $value);
        }
    }

    public function scopePublished($query, $src, $dst){
        if ($src != ''){
            if ($dst != ''){
                $query->where('odors.created_at', '>=', date('Y-m-d H:i:s', strtotime($src)))->where('odors.created_at', '<=', date('Y-m-d H:i:s', strtotime($dst)));
            } else {
                $query->where('odors.created_at', '>=', date('Y-m-d H:i:s', strtotime($src)));
            }
        } else {
            if ($dst != '') {
                $query->where('odors.created_at', '<=', date('Y-m-d H:i:s', strtotime($dst)));
            }
        }
    }
}
