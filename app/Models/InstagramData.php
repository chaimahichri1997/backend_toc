<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramData extends Model
{
    protected $fillable = ['code','comments','likes','path','image'];

    protected $table = 'instagrams';

}
