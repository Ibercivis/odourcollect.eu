<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointOfInterest extends Model implements Auditable
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
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'points_of_interest';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_point_of_interest_type', 'id_admin', 'name', 'slug', 'address', 'address__mapbox_id', 'postal_code', 'postal_code__mapbox_id', 'place', 'place__mapbox_id', 'region', 'region__mapbox_id', 'country', 'country__mapbox_id', 'latitude', 'longitude', 'published_at'
    ];

    /* RELACIONS N-M */
    public function zones()
    {
        return $this->belongsToMany('App\Zone', 'point_of_interest_zones', 'id_point_of_interest', 'id_zone')->withPivot('verified')->withTimestamps();
    }

    /** FILTERS **/
    public function scopeZone($query, $id_zone)
    {
        if(!empty($id_zone)){
            return $query->whereHas('zones', function ($query) use ($id_zone) {
                $query->where('zones.id', $id_zone);
            });
        }
    }
}
