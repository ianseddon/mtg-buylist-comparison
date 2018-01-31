<template>
    <div>
        <a v-if="loading" class="button is-small is-loading" style="border: none"></a>
        <span v-if="!loading && found">{{ displayPrice }}</span>
        <a :title="notFoundReason" class="has-text-danger" v-if="!loading && !found">N/A</a>
    </div>
</template>

<script>
import echo from '../mixins/echo';

export default {
    name: 'card-list-item-buy-price',

    mixins: [echo],

    props: {
        id: { required: true },
        vendor: { required: true },
        quantity: { default: 1 },
    },

    data() {
        return {
            loading: true,
            found: false,
            notFoundReason: '',
            mutableBuyOrder: {},
        }
    },

    computed: {
        displayPrice() {
            let singleRawPrice = this.mutableBuyOrder.price;
            let singlePrice = this.formatCurrency(singleRawPrice);

            if (this.quantity < 2) {
                return singlePrice;
            }

            let totalRawPrice = singleRawPrice * this.quantity;
            let totalPrice = this.formatCurrency(totalRawPrice);

            return totalPrice + ' (' + singlePrice + ')';
        }
    },

    created() {
        // Listen for buy order lookup completion.
        this.$http.get('/api/vendor/' + this.vendor + '/lookup/' + this.id);
    },

    methods: {

        getEventHandlers() {
            return {
                'BuyOrderFound': response => {
                    let event = response;

                    if (!this.shouldHandleFoundEvent(event)) {
                        return;
                    }

                    this.handleFoundEvent(event);
                },

                'BuyOrderNotFound': response => {
                    let event = response;

                    if (!this.shouldHandleNotFoundEvent(event)) {
                        return;
                    }

                    this.handleNotFoundEvent(event);
                },
            };
        },

        shouldHandleFoundEvent(e) {
            return (e.cardListItem.id == this.id && e.vendorSite.id == this.vendor);
        },

        handleFoundEvent(e) {
            this.mutableBuyOrder = e.buyOrder;
            this.found = true;
            this.loading = false;
        },

        shouldHandleNotFoundEvent(e) {
            return (e.cardListItem.id == this.id && e.vendorSite.id == this.vendor);
        },

        handleNotFoundEvent(e) {
            this.loading = false;
            this.found = false;
            this.notFoundReason = e.message;
        },

        formatCurrency(raw) {
            return raw.toLocaleString('en-US', {
                style: 'currency',
                currency: 'USD',
            });
        }
    }
}
</script>

<style>

</style>