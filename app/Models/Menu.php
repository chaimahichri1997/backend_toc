<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Menu extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'url',
        'order',
        'parent_id'
    ];

    public function setSlugAttribute($value)
    {   
        $this->attributes['slug'] = Str::slug($this->title, '-');
    }

    public function sub_menu()
    {
        return $this->hasMany(Menu::class,'parent_id','id')->orderBy('order');
    }

}
