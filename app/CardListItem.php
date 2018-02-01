<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reference\Card;

class CardListItem extends Model
{
    protected $with = ['card'];
    protected $fillable = ['quantity', 'card_id', 'foil', 'language', 'condition'];

    /**
     * Define the relationship to the card info.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'multiverse_id');
    }

    /**
     * Define the relationship to the list containing this item.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function list()
    {
        return $this->belongsTo(CardList::class);
    }
}
