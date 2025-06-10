<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Booking extends Model
{
       protected $guarded=[];


       public function Event()
       {
        return $this->belongsTo(Event::class);
       }

}
