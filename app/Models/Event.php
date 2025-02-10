<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $table = 'events';

    // Specify the primary key
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_category',
        'event_title',
        'event_location',
        'event_venue',
        'event_start_time',
        'event_end_time',
        'event_date',
        'event_description',
        'event_card',
        'event_code',
        'event_qrcode',
        'event_status',
        'notifications_whatsapp',
        'notifications_email',
        'notifications_sms',
    ];
    //
}
