<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdorParentType extends Model implements Auditable
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
        'id', 'name', 'slug'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatable  = [
        'name', 'slug',
    ];


    /* RELACIONS 1-N */

    public function childs()
    {
        return $this->hasMany('App\OdorType', 'id_odor_parent_type', 'id');
    }
}
