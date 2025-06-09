<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'short_description',
        'long_description',
        'datetime',
        'datetime_start',
        'datetime_end',
        'capacity',
        'is_time_set',
        'is_date_range',
        'location',
        'show_location_map',
        'url_web_page',
        'highlighted',
        'filename',
        'views'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
