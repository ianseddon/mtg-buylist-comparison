<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    // Use the multiverse ID as the primary ID.
    public $primaryKey = 'multiverse_id';
    public $incrementing = false;

    protected $fillable = ['multiverse_id', 'name'];

    /**
     * Define the relationship to this card's set.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function set()
    {
        return $this->belongsTo(Set::class);
    }
}
