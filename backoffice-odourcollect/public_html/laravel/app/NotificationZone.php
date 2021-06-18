<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class NotificationZone extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'id', 'zone_id', 'number_observations', 'hours', 'min_hedonic_tone', 
        'max_hedonic_tone', 'min_intensity', 'max_intensity', 
    ];
}
