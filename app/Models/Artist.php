<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Artist extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'type',
        'region',
        'image',
        'born_year',
        'death_year',
        'born_location',
        'about_artist',
        'website',
        'quote',
        'quote_by',
        'represented_by',
        'collections',
        'major_shows',
        'author_id',
        'status'
    ];

    protected static $logAttributes = [
        'name',
        'type',
        'region',
        'image',
        'born_year',
        'death_year',
        'born_location',
        'about_artist',
        'website',
        'quote',
        'quote_by',
        'represented_by',
        'collections',
        'major_shows',
        'status',
    ];

    protected static $logName = 'Artist';

    public function getDescriptionForEvent(string $eventName): string
    {
        return $this->name . " artist has been succesfully {$eventName}";
    }

    public function art_works()
    {
        return $this->hasMany(ArtWork::class,'artist_id','id');
    }
}
