<section class="hero is-primary is-small">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                List Title
            </h1>
        </div>
    </div>
    <div class="hero-foot">
        <nav class="tabs is-boxed">
            <div class="container">
                <ul>
                    <li>
                        <a href="{{ action('CardListController@show', ['cardList' => $card_list_id]) }}">
                            Manage
                        </a>
                    </li>
                    {{--  <li>
                        <a href="{{ action('CardListController@showImport', ['cardList' => $card_list_id]) }}">
                            Import
                        </a>
                    </li>  --}}
                    <li>
                        <a href="{{ action('CardListController@sell', ['cardList' => $card_list_id]) }}">
                            Sell
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</section>