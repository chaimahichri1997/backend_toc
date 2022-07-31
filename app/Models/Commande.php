<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Commande extends Model
{
    use SoftDeletes, LogsActivity;

    

    protected $fillable = [
        'description',
        'user_name',
        'place',
        'total_price',
       
    ];

    protected static $logAttributes = [
        'description',
        'user_name',
        'place',
        'total_price',
    ];

    protected static $logName = 'Commande';


}
