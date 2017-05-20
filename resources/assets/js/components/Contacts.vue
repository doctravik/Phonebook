<template>
    <div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Contacts</h3>
                </div>

               <ul class="media-list" v-if="hasContacts">
                    <li class="media" v-for="contact in contacts">
                        <div class="media__left">
                            <img class="media__image" src="http://placehold.it/64x64">
                        </div>
                        <div class="media__body">
                            <a href="#"><strong>{{ contact.name }}</strong></a>
                        </div>
                        <div class="media__right">
                            <span class="btn btn-success" @click="updateContact(contact)">Edit</span>
                            <span class="btn btn-default" @click="deleteContact(contact)">Delete</span>
                        </div>
                    </li>
                </ul>

                <ul class="media-list" v-else>
                    <li class="media">You have no contacts</li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <create-contact-form @add-contact="addContact"></create-contact-form>
        </div>
    </div>
</template>

<script>
    import CreateContactForm from './CreateContactForm.vue';

    export default {
        computed: {
            /**
             * @return boolean
             */
            hasContacts () {
                return this.contacts && this.contacts.length;
            },
        },

        /**
         * Created event of component.
         * 
         * @return void
         */
        created() {
            this.selectContactsFromDb();
        },

        data() {
            return {
                contacts: null
            }
        },

        methods: {
            /**
             * @TODO add error message
             * @return void
             */
            selectContactsFromDb() {
                axios.get('/api/contacts')
                    .then(response => {
                        this.contacts = response.data.data;
                    })
                    .catch(error => {
                        //
                    })
            },

            /**
             * Add contact on client side.
             * 
             * @param  object contact
             * @return void
             */
            addContact(contact) {
                this.contacts.push(contact);
            },

            updateContact() {

            },

            /**
             * Remove contact on server side.
             * 
             * @param  object contact
             * @return void
             */
            deleteContact(contact) {
                axios.delete('/api/contacts/' + contact.id)
                    .then(response => {
                        this.removeContact(contact);
                    })
                    .catch(error => {
                        //
                    })
            },

            /**
             * Remove contact on client side.
             * 
             * @param  object contact
             * @return void
             */
            removeContact(contact) {
                this.contacts = this.contacts.filter(user => user.id != contact.id);
            }
        },

        components: { CreateContactForm }
    }
</script>

<style scoped>
    .media-list {
        margin: 0;
        padding: 0;
    }

    .media {
        display: flex;
        align-items: center;
        padding: 1em;
        margin: 0;
    }

    .media + .media {
        border-top: 1px solid #CCC;
    }

    .media__body {
        margin: 0 1em 0 1em;
    }

    .media__right {
        margin-left: auto;
    }
</style>