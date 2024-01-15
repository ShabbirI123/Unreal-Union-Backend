<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'rating';

    protected $primaryKey = 'rating_id';

    protected $fillable = [
        'rating'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
