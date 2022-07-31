<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBasket extends Model
{
    use HasFactory;

    protected $table = 'user_basket';


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
