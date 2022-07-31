<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class CulturalIncubator extends Model
{
    use LogsActivity;

    protected $fillable = [
        'images',
        'start_date',
        'end_date',
        'language',
        'title',
        'subtitle',
        'description',
        'status',
        'slug'
    ];

    protected static $logAttributes = [
        'images',
        'start_date',
        'end_date',
        'language',
        'title',
        'subtitle',
        'description',
        'status',
        'slug'
    ];

    protected $casts=['images'=>'array'];

    protected static $logName = 'Cultural_Incubator';

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function setSlugAttribute($value)
    {   
        $this->attributes['slug'] = Str::slug($this->title, '-');
    }
}
