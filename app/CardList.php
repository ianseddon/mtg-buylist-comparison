<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardList extends Model
{
    protected $fillable = ['name'];

    public function listable()
    {
        return $this->morphTo();
    }
}
