<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;


class BasicPage extends Model
{
    use HasTranslations, LogsActivity;


    public $translatable = [
        'name',
        'title',
        'subtitle',
        'description',
        'body',
    ];

    protected $fillable = [
        'name',
        'title',
        'slug',
        'subtitle',
        'publication_status',
        'description',
        'body',
        'image',
        'author_id'
    ];

    protected static $logAttributes = [
        'name',
        'title',
        'slug',
        'subtitle',
        'publication_status',
        'description',
        'body',
        'image',
        'author_id'
    ];

    protected static $logName = 'Basic_Pages';

    protected $hidden = ['user'];

    protected $appends = ['CreatedBy'];

    public function getCreatedByAttribute()
    {
        return ($this->user != null)?$this->user->first_name : null;
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($this->name, '-');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('publication_status', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class,'page_id','id');
    }

}
