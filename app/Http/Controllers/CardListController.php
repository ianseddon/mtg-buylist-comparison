<?php

namespace App\Http\Controllers;

use App\CardList;
use App\VendorSite;
use App\Jobs\BuyPriceLookup;

class CardListController extends Controller
{
    /**
     * Show the card list for the current user's collection.
     *
     * @return Illuminate\Http\Response
     */
    public function showCollection()
    {
        return $this->show(auth()->user()->collection);
    }

    public function showImport(CardList $cardList)
    {
        return view('card-list.import', ['card_list_id' => $cardList->id]);
    }

    public function sell(CardList $cardList)
    {
        // TODO: Don't hardcode this.
        $cardsToSell = $cardList->cards;

        // TODO: Don't hardcode this.
        $vendorsToSellTo = VendorSite::all();

        /*
        // Dispatch price lookup jobs.
        foreach ($cardsToSell as $cardToSell) {
            foreach ($vendorsToSellTo as $vendorToSellTo) {
                dispatch(new BuyPriceLookup($cardToSell, $vendorToSellTo));
            }
        }
        */

        return view('card-list.sell', [
            'card_list_id' => $cardList->id,
            'cards' => $cardsToSell,
            'vendors' => $vendorsToSellTo,
        ]);
    }

    /**
     * Show the given card list.
     *
     * @param CardList $cardList
     * @return Illuminate\Http\Response
     */
    public function show(CardList $cardList)
    {
        // Is the user authorized to view this list?
        if (!$this->authorizedToView($cardList, auth()->user())) {
            abort(403, 'Forbidden');
        }

        return view('card-list.show', ['card_list_id' => $cardList->id]);
    }

    /**
     * Is the given user authorized to view the given card list?
     *
     * @param CardList $card
     * @param App\User $user
     * @return bool
     */
    protected function authorizedToView(CardList $cardList, \App\User $user)
    {
        return $cardList->listable->id == $user->id;
    }
}
