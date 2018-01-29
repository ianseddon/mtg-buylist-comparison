<?php

namespace App\Http\Controllers;

use App\CardList;

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
