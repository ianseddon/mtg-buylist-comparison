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

    public function cards()
    {
        return $this->hasMany(CardListItem::class);
    }
}
