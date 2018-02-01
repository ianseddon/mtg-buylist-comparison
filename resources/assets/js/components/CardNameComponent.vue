<template>
    <div class="card-name">
        <div @click="startEditing">
            <a v-if="!editing">{{ this.card.name }}</a>
        </div>
        <input type="text" class="input" placeholder="Card Name"
           autocomplete="off"
           v-if="editing"
           v-model="query"
           v-focus
           @keydown.down="down"
           @keydown.up="up"
           @keydown.enter="hit"
           @keydown.esc="stopEditing"
           @blur="stopEditing"
           @input="update"/>

        <ul class="options-list" v-show="hasItems">
            <!-- for vue@1.0 use: ($item, item) -->
            <li v-for="(item, $item) in items" :class="activeClass($item)" @mousedown="hit" @mousemove="setActive($item)">
                <span>{{ item.name }}</span>
                <span class="help">({{ item.setName }})</span>
            </li>
        </ul>
    </div>
</template>

<script>
import VueTypeahead from 'vue-typeahead';

export default {
    extends: VueTypeahead,
    name: 'card-name',
    model: {
        prop: 'value',
    },
    props: {
        value: { required: true },
    },
    data() {
        return {
            editing: !this.validate(this.value),

            card: this.value,

            // The source url
            src: 'https://api.magicthegathering.io/v1/cards',

            // Limit the number of items which is shown at the list
            limit: 5,

            // The minimum character length needed before triggering
            minChars: 3,

            // Highlight the first item in the list
            selectFirst: true,

            // Override the default value (`q`) of query parameter name
            queryParamName: 'name'
        }
    },
    created() {
        this.card = this.value;
    },
    methods: {
        // The callback function which is triggered when the response data is received.
        prepareResponseData (data) {
            data = data.cards;
            return data;
        },

        // Callback function triggered when an autocomplete option is chosen.
        onHit (item) {
            let card = {
                multiverse_id: item.multiverseid,
                name: item.name,
                set: { name: item.setName },
            };

            // Check the chosen option is valid.
            if (!this.validate(card)) {
                return;
            }

            // Set the card to the chosen item.
            this.card = card;

            // Finish editing.
            this.stopEditing();
        },

        // The user has clicked on the field to start editing.
        startEditing() {
            this.editing = true;
        },
        validate(card) {
            return card.hasOwnProperty('multiverse_id') && card.multiverse_id;
        },
        stopEditing() {
            // Don't submit changes if new value is not valid or if we are not editing.
            if (!this.validate(this.card) || !this.editing) {
                return;
            }

            // Reset the autocomplete text field.
            this.reset();

            // Stop editing.
            this.editing = false;

            // Emit change event.
            this.$emit('input', this.card);
        }
    }
}
</script>

<style>
.card-name {
    position: relative;
}

.card-name a {
    border-bottom: 1px #DDD dotted
}

.card-name ul.options-list {
    padding: 0;
    z-index: 999;
    display: flex;
    flex-direction: column;
    margin-top: -1px;
    border: 1px solid #dbdbdb;
    border-radius: 0 0 3px 3px;
    position: absolute;
    width: 100%;
    overflow: hidden;
}

.card-name ul.options-list li {
    display: inline-block;
    margin: 0 10px;
    width: 100%;
    flex-wrap: wrap;
    background: white;
    margin: 0;
    border-bottom: 1px solid #eee;
    color: #363636;
    padding: 7px;
    cursor: pointer;
}

.card-name ul.options-list li.active, .card-name ul.options-list li:hover {
    background: #f8f8f8;
}
</style>