<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardListItem extends Model
{
    protected $fillable = ['quantity', 'set', 'name', 'foil', 'language', 'condition'];

    public function list()
    {
        return $this->belongsTo(CardList::class);
    }
}
