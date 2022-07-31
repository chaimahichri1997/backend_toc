<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contact extends Model
{
    use Notifiable;
    protected $fillable = [
        'name',
        'country',
        'email',
        'phone',
        'message',
    ];
}
