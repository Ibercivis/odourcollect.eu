<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdorType extends Model implements Auditable
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
        'id', 'id_odor_parent_type', 'name', 'slug', 'icon', 'color'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatable  = [
        'name', 'slug',
    ];


    /* 1 - 1 RELATIONS */

    public function parent()
    {
        return $this->hasOne('App\OdorParentType', 'id', 'id_odor_parent_type');
    }


    /** FILTERS **/

    public function scopeParents($query, $ids)
    {
        if(!empty($ids)){
            $query->whereIn('id_odor_parent_type', $ids);
        }
    }
}
