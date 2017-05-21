<template>
    <div class="phones">
        <phone v-if="hasPhones" v-for="phone in phones" :key="phone.phone_number" 
            :phone="phone" 
            @remove-phone="removePhone">
        </phone>

        <create-phone-form :contact="contact" v-if="showCreatePhoneForm" 
            @add-phone="addPhone"
            @cancel-creating="showCreatePhoneForm=false">
        </create-phone-form>

        <div v-if="!hasPhones && !showCreatePhoneForm">
            <span class="phones__text"><strong>Contact has no phone numbers</strong></span>
        </div>

        <div class="phone__new-button" v-show="!showCreatePhoneForm">
            <button class="btn btn-info" @click="showPhoneForm">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create new phone
            </button>
        </div>
    </div>
</template>

<script>
    import Phone from './Phone.vue';
    import CreatePhoneForm from './CreatePhoneForm.vue';

    export default {
        props: ['contact'],

        computed: {
            hasPhones() {
                return this.phones.length > 0;
            }
        },

        data() {
            return {
                phones: this.contact.phones.data,
                showCreatePhoneForm: false
            }
        },

        methods: {
            /**
             * @return void
             */
            showPhoneForm() {
                if (!this.showCreatePhoneForm) {
                    this.showCreatePhoneForm = true;
                }
            },

            /**
             * Add contact on the client side.
             * 
             * @param object phone
             * @return void
             */
            addPhone(phone) {
                this.showCreatePhoneForm = false;
                this.phones.push(phone);
            },

            /**
             * Remove contact from the client side.
             * 
             * @param int phoneId
             * @return void
             */
            removePhone(phoneId) {
                this.phones = this.phones.filter(phone => phone.id != phoneId);
            }
        },

        components: { Phone, CreatePhoneForm }
    }
</script>