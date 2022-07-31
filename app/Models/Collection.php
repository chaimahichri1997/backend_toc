<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Collection extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'tags',
        'image',
        'author_id',
        'parent_id',
        'status',
        'favorite'
    ];

    protected static $logAttributes = [
        'name',
        'tags',
        'image',
        'parent_id',
        'status',
        'favorite'


    ];

    protected static $logName = 'Collection';

    public function getDescriptionForEvent(string $eventName): string
    {
        return $this->name . " collection has been succesfully {$eventName}";
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }


    public function sub_collections()
    {
        return $this->hasMany(SubCollection::class, 'collection_id', 'id');
    }
}
