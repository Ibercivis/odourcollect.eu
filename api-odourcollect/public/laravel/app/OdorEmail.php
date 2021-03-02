<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OdorEmail extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user', 'subject', 'location'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatable  = [
        'user', 'subject', 'location'
    ];

}
