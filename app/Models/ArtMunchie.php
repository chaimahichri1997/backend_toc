<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ArtMunchie extends Model
{
    use HasTranslations, LogsActivity;

    public $translatable = ['title','body'];


    protected $fillable = [
        'title',
        'body',
        'url',
        'image',
        'author_id',
        'date',
        'category',
        'region'
    ];

    protected static $logAttributes = [
        'title',
        'body',
        'url',
        'image',
        'date',
        'category',
        'region'
    ];

    protected static $logName = 'Art_Munchies';

    public function getDescriptionForEvent(string $eventName): string
    {
        return $this->name . " art munchie has been succesfully {$eventName}";
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

}
