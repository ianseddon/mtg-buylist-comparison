<template>
    <tr class="card-list-item">
        <td>
            <card-quantity v-model="mutableCard.quantity" @input="cardQuantityChanged"></card-quantity>
        </td>
        <td>
            <card-name v-model="mutableCard.card" @input="cardNameChanged"></card-name>
        </td>
        <td>
            {{ mutableCard.card.set.name }}
        </td>
        <td>
            <input class="checkbox" type="checkbox" @change="cardIsFoilChanged" v-model="mutableCard.foil">
        </td>
        <td>
            <a title="Remove" @click="removeItem" class="has-text-danger">
                &times;
            </a>
        </td>
    </tr>
</template>

<script>
import CardQuantityComponent from './CardQuantityComponent.vue';
import CardNameComponent from './CardNameComponent.vue';

export default {
    name: 'card-list-item',
    components: {
        'card-quantity': CardQuantityComponent,
        'card-name': CardNameComponent,
    },
    model: {
        prop: 'card',
    },
    props: {
        card: {
            required: true,
            default: function () {
                return {
                    quantity: 1,
                    card: {
                        multiverse_id: null,
                        name: '',
                        set: { name: '' },
                    },
                    foil: false,
                }
            }
        }
    },
    data() {
        return {
            // The mutable card object instance.
            mutableCard: {}
        }
    },
    created() {
        // Copy the card property to have a mutable instance.
        this.mutableCard = Object.assign({}, this.card);
    },
    methods: {
        cardNameChanged() {
            this.mutableCard.card_id = this.mutableCard.card.multiverse_id;
            this.updateItem();
        },
        cardQuantityChanged() {
            this.updateItem();
        },
        cardIsFoilChanged() {
            this.updateItem();
        },
        updateItem() {
            // TODO: Remove this once we aren't faking a more complex
            // card data structure.
            // Flatten the card data structure.
            let cardData = this.mutableCard;

            // Only update if the card already has an ID set (already exits in DB).
            if (typeof this.mutableCard.id !== 'undefined') {
                axios.patch('/api/list/' + this.mutableCard.card_list_id + '/cards/' + this.mutableCard.id, cardData);
            }
            // Send a post request adding the card to the list.
            else {
                axios.post('/api/list/' + this.mutableCard.card_list_id + '/cards', cardData)
                    .then((response) => this.mutableCard = response.data);
            }

            // Emit a change event.
            this.$emit('input', cardData);
        },
        removeItem() {
            let item = this;

            if (typeof this.mutableCard.id !== 'undefined') {
                axios.delete('/api/list/' + this.mutableCard.card_list_id + '/cards/' + this.mutableCard.id)
                    .then(function (r) {
                        item.$emit('remove');
                    });
            }
            else {
                item.$emit('remove');
            }
        }
    }
}
</script>

<style>
.card-list-item td {
    vertical-align: middle;
}
</style>