<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model implements Auditable
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
        'id', 'id_odor', 'id_user', 'comment'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatable  = [
       'comment'
    ];

    /** FILTERS **/

    public function scopeUser($query, $user){
        if($user != ''){
            $query->where('id_user', $user);
        }
    }

    public function scopeOdor($query, $odor){
        if($odor != ''){
            $query->where('id_odor', $odor);
        }
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'id_user');
    }

}