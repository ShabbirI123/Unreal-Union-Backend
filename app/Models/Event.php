<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';

    protected $primaryKey = 'event_id';

    protected $fillable = [
        'name',
        'description',
        'location',
        'date',
    ];

}
