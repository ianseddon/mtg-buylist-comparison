<template>
    <div>
        <div class="control">
            <button class="button is-primary" @click="addCardItem">+ Card</button>
        </div>
        <table class="table is-fullwidth is-hoverable">
            <thead>
                <tr>
                    <th>Quantity</th>
                    <th>Name</th>
                    <th>Set</th>
                    <th>Foil</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <card-list-item
                    v-for="(card, index) in cards"
                    :key="card.id"
                    v-model="card.data"
                    @remove="removeCardItem(card)"
                    card="card">
                </card-list-item>
            </tbody>
        </table>
    </div>
</template>

<script>
import CardListItem from './CardListItemComponent.vue';

export default {
    name: 'card-list',
    components: { CardListItem },
    props: {
        id: {
            required: true,
            default: 29
        }
    },
    data() {
        return {
            cards: [],
        }
    },
    created() {
        axios.get('/api/list/' + this.id + '/cards').then(response => this.cards = response.data.map(function (c) {
            return { data: c };
        }));
    },
    methods: {
        addCardItem() {
            this.cards.push({
                data: {
                    'quantity': 1,
                    card: {
                        'name': '',
                        'set': '',
                    },
                    'card_list_id': this.id,
                }
            });
        },
        removeCardItem(cardToRemove) {
            this.cards = this.cards.filter(card => card !== cardToRemove);
        }
    }
}
</script>

<style>

</style>