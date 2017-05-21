<template>
    <div class="phone">
        <div class="phone__label">
            <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>
        </div>
        <div class="phone__content" :class="{'has-error': errors.has('phone_number')}">
            <div class="phone__number">
                <masked-input class="form-control" v-model="phoneNumber" 
                    mask="\+1 (111) 111-11-11" placeholder="Phone"/>
            </div>
            <div class="phone__buttons">
                <div class="btn-toolbar" role="toolbar"> 
                    <button type="button" class="btn btn-default" @click="updatePhone">
                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-default" @click="deletePhone">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-default" @click="resetForm" v-if="errors.any()">
                        <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <span class="help-block phone__error" v-if="errors.has('phone_number')">
                <strong v-text="errors.get('phone_number')"></strong>
            </span>
        </div>
    </div>
</template>

<script>
    import Form from './Form.js';
    import MaskedInput from 'vue-masked-input';

    export default {
        props: ['phone'],

        data() {
            return {
                phoneNumber: this.phone.phone_number
            }
        },

        methods: {
            /**
             * Update phone number in db.
             *
             * @return void
             */
            updatePhone() {
                axios.patch('/api/phones/' + this.phone.id, this.form())
                    .then(response => {
                        this.phone.phone_number = this.phoneNumber;
                    })
                    .catch(error => {
                        this.errors.record(error.response.data);
                    });
            },

            /**
             * Delete phone number from db.
             *
             * @TODO error message
             * @return void
             */
            deletePhone() {
                axios.delete('/api/phones/' + this.phone.id)
                    .then(response => {
                        this.$emit('remove-phone', this.phone.id);
                    })
                    .catch(error => {
                        //
                    });
            },

            /**
             * Reset form value.
             * 
             * @return void
             */
            resetForm() {
                this.errors.clear();
                this.phoneNumber = this.phone.phone_number;
            }
        },

        mixins: [ Form ],

        components: { MaskedInput }
    }
</script>