<?php

namespace App\Http\Controllers\Api;

use App\CardList;
use App\CardListItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CardListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CardList $list)
    {
        return response()->json($list->cards->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CardList $list)
    {
        $cardListItem = $list->cards()->create($request->input());

        return response()->json($cardListItem->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CardListItem  $cardListItem
     * @return \Illuminate\Http\Response
     */
    public function show($cardListId, $cardListItemId)
    {
        $cardListItem = CardListItem::findOrFail($cardListItemId);

        return response()->json($cardListItem->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CardListItem  $cardListItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cardListId, $cardListItemId)
    {
        $cardListItem = CardListItem::findOrFail($cardListItemId);
        $cardListItem->update($request->input());

        return response()->json($cardListItem->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CardListItem  $cardListItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($cardListId, $cardListItemId)
    {
        $cardListItem = CardListItem::findOrFail($cardListItemId);

        $deleted = $cardListItem->delete();

        if ($deleted) {
            return response('Deleted');
        } else {
            return response('Could not delete card from list.', 500);
        }
    }
}
