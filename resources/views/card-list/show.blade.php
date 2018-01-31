@extends('layouts.app')

@section('hero')
    @include('card-list.partials.hero')
@endsection

@section('content')

<section class="section">
    <div class="container is-fluid">
        <card-list id="{{ $card_list_id }}"></card-list>
    </div>
</section>

@endsection