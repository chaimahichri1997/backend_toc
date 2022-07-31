<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ArtWork extends Model
{
    use SoftDeletes, LogsActivity;

    const AVAILABLE = 'Available';
    const ONLOAN = 'On Loan';
    const SOLD = 'Sold';

    protected $fillable = [
        'image',
        'title',
        'reference',
        'date',
        'category',
        'medium',
        'dimensions',
        'edition',
        'provenance',
        'height',
        'width',
        'depth',
        'framing',
        'makers_marks',
        'production_place',
        'description',
        'status',
        'artist_id',
        'author_id',
        'InExplore',
        'price'
    ];

    protected static $logAttributes = [
        'image',
        'title',
        'reference',
        'date',
        'category',
        'medium',
        'dimensions',
        'edition',
        'provenance',
        'height',
        'width',
        'depth',
        'framing',
        'makers_marks',
        'production_place',
        'description',
        'status',
        'artist_id',
        'author_id',
        'InExplore',
        'price',
        'InBasket',
        'etat'


    ];

    protected static $logName = 'Artwork';


    public function collection()
    {
        return $this->belongsToMany(Collection::class, 'collection_artworks', 'collection_id', 'artwork_id');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'artwork_id', 'id');
    }


    public function user_favorit()
    {
        return $this->belongsToMany(User::class, 'art_work_user');
    }

    public function artwork_in_basket()
    {
        return $this->belongsToMany(User::class, 'user_basket');
    }
}
