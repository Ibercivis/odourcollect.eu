<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationEmailModel extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zone_id', 'subject', 'body'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatable  = [
        'zone_id', 'subject', 'body'
    ];

}
