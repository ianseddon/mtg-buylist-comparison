<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $fillable = ['name', 'code'];

    /**
     * Define the relationship to the cards in this set.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
