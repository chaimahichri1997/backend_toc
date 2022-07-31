<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'body',
        'page_id'
    ];
    
    protected $casts=['body'=>'array'];

    public function page()
    {
        return $this->belongsTo(BasicPage::class, 'page_id', 'id');
    }

}
