<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserArtwork extends Model
{
    use HasFactory;

    protected $table = 'art_work_user';


    protected $fillable = [
        'user_id',
        'art_work_id'

    ];

    public function artwork()
    {
        return $this->belongsTo(ArtWork::class, 'art_work_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
