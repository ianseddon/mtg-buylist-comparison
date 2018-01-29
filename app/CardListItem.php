<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardListItem extends Model
{
    protected $appends = ['card'];
    protected $fillable = ['quantity', 'set', 'name', 'foil', 'language', 'condition'];

    public function getCardAttribute()
    {
        return [
            'name' => $this->name,
            'set' => $this->set,
        ];
    }

    public function list()
    {
        return $this->belongsTo(CardList::class);
    }
}
