<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SubCollection extends Model
{
    use SoftDeletes, LogsActivity;

    const AVAILABLE = 'Available';
    const ONLOAN = 'On Loan';
    const SOLD = 'Sold';

    protected $fillable = [
        'image',
        'title',
        'reference',
        'artwork_id',
        'author_id',
        'collection_id'
    ];

    protected static $logAttributes = [
        'image',
        'title',
        'reference',
        'artwork_id',
        'author_id',
        'collection_id'
    ];

    protected static $logName = 'subcollection';

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function collections()
    {
        return $this->belongsTo(Collection::class, 'collection_id', 'id');
    }


    public function art_works()
    {
        return $this->hasMany(ArtWork::class, 'subcollection_id', 'id');
    }
}
