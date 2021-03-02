<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    use Notifiable;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

     protected $guard = 'web';
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'password', 'is_verified', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /* RELACIONS N-M */

    public function zones()
    {
        return $this->belongsToMany('App\Zone', 'user_zones', 'id_user', 'id_zone')->withTimestamps();
    }


    /** FILTERS **/

    public function scopeStatus($query, $status)
    {
        if ($status != ''){
            $query->where('active', $status);
        }
    }

    public function scopePermission($query, $permission)
    {
        if ($permission != ''){
            $query->where('without_validation', $permission);
        }
    }

    public function scopeMap($query, $map)
    {
        if ($map != ''){
            $query->join('user_zones', 'users.id', '=', 'user_zones.id_user')->where('id_zone', $map);
        }
    }

    public function scopeRegistered($query, $src, $dst){
        if ($src != ''){
            if ($dst != ''){
                $query->where('users.created_at', '>=', date('Y-m-d H:i:s', strtotime($src)))->where('users.created_at', '<=', date('Y-m-d H:i:s', strtotime($dst)));
            } else {
                $query->where('users.created_at', '>=', date('Y-m-d H:i:s', strtotime($src)));
            }
        } else {
            if ($dst != '') {
                $query->where('users.created_at', '<=', date('Y-m-d H:i:s', strtotime($dst)));
            }
        }
    }

    public function scopePublishing($query, $src, $dst)
    {
        if ($src != ''){
            if ($dst != ''){
                $query->join('odors', 'users.id', '=', 'odors.id_user')->where('odors.created_at', '>=', date('Y-m-d H:i:s', strtotime($src)))->where('odors.created_at', '<=', date('Y-m-d H:i:s', strtotime($dst)));
            } else {
                $query->join('odors', 'users.id', '=', 'odors.id_user')->where('odors.created_at', '>=', date('Y-m-d H:i:s', strtotime($src)));
            }
        } else {
            if ($dst != '') {
                $query->join('odors', 'users.id', '=', 'odors.id_user')->where('odors.created_at', '<=', date('Y-m-d H:i:s', strtotime($dst)));
            }
        }
    }

}
