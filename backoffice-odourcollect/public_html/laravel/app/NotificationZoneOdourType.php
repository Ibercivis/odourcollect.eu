<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class NotificationZoneOdourType extends Model
{
    protected $fillable = [
        'id', 'id_odour_type', 'id_notification_zone', 
    ];
}
