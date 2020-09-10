<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderSession extends Model
{
    protected $fillable = ['order', 'active', 'key', 'user_id'];

    protected $table = 'order_session';
}
