<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionConfiguration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'fields'
    ];

    protected $casts=['fields'=>'array'];


}
