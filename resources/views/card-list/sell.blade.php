@extends('layouts.app')

@section('hero')
    @include('card-list.partials.hero')
@endsection

@section('content')

<section class="section">
    <div class="container is-fluid">
        <table class="table is-fullwidth">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Set</th>
                    @foreach($vendors as $vendor)
                    <th style="text-align: center">
                        {{ $vendor->name }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($cards as $card)
                <tr>
                    <td>
                        {{ $card->quantity }}
                    </td>
                    <td>
                        {{ $card->name }}
                    </td>
                    <td>
                        {{ $card->set }}
                    </td>
                    @foreach($vendors as $vendor)
                    <td style="text-align: center">
                        <card-list-item-buy-price
                            id="{{ $card->id }}"
                            vendor="{{ $vendor->id }}"
                            quantity="{{ $card->quantity }}">
                        </card-list-item-buy-price>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@endsection