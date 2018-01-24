<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuyOrder extends Model
{
    protected $fillable = ['card_name', 'card_set', 'buying', 'price', 'foil'];
}
