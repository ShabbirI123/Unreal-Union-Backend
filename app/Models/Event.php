<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'category',
        'participation_limit',
        'creator_user_id'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, foreignKey: 'event_id');
    }
}
