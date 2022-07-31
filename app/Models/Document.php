<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Document extends Model
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
        'author_id'

    ];

    protected static $logAttributes = [
        'image',
        'title',
        'reference',
        'artwork_id',
        'author_id'


    ];

    protected static $logName = 'Document';

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function artworks()
    {
        return $this->belongsTo(Artwork::class, 'artwork_id', 'id');
    }
}
