<template>
    <div class="card-quantity" @click="startEditing">
        <a href="#" v-if="!editing">{{ mutableValue }}</a>
        <input type="number" min="1" class="input" size="4"
               v-if="editing"
               v-focus
               v-model="mutableValue"
               @blur="doneEditing"
               @keyup.enter="doneEditing">
    </div>
</template>

<script>
export default {
    name: 'card-quantity',
    model: {
        prop: 'value',
    },
    props: {
        value: {
            default: ""
        }
    },
    data() {
        return {
            mutableValue: "",
            editing: false
        }
    },
    created() {
        this.mutableValue = this.value;
    },
    methods: {
        startEditing() {
            this.editing = true;
        },
        validate(value) {
            var numberValue = Number(this.mutableValue);

            return this.mutableValue && Number.isInteger(numberValue) && numberValue >= 0;
        },
        doneEditing() {
            if (!this.validate(this.mutableValue) || !this.editing) {
                return;
            }
            this.editing = false;
            this.$emit('input', this.mutableValue);
        }
    }
}    
</script>

<style>
    .card-quantity input {
        max-width: 6em;
    }
</style>