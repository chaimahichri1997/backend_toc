<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CulturalIncubatorRequest extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_name',
        'company',
        'country',
        'full_name',
        'occupation',
        'email',
        'phone',
        'message'
    ];

}
