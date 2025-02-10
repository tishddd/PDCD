<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    // Specify the table name
    protected $table = 'event_members';

    // Define the primary key (optional if it's 'id')
    protected $primaryKey = 'member_id';

    // Enable timestamps (set to false if not using created_at and updated_at columns)
    public $timestamps = true;

    // Define the fields that are fillable for mass assignment protection
    protected $fillable = [
        'event_id',
        'member_name',
        'member_phone',
        'member_email',
        'rsvp_status',
        'status',
        'total',
    ];

    // Optionally define cast types for other fields if needed
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Define the relationship with the Event model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    // Optionally, you can define an accessor for full name or custom methods
    // For example, a method to get the full name of a member
    public function getFullNameAttribute()
    {
        return $this->member_name; // or combine first/last name if applicable
    }
}
