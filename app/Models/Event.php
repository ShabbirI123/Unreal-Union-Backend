<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'image_path',
        'category'
    ];

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id');
    }
}
