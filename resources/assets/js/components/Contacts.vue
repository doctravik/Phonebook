<template>
    <div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Contacts</h3>
                </div>

               <ul class="media-list" v-if="hasContacts">
                    <contact v-for="contact in contacts" 
                        :key="contact.id" 
                        :contact="contact" 
                        @remove-contact="removeContact">
                    </contact>
                </ul>

                <ul class="media-list" v-else>
                    <li class="media">You have no contacts</li>
                </ul>
            </div>
        </div>

        <div class="col-md-4" v-if="showCreateForm">
            <create-contact-form @add-contact="addContact"></create-contact-form>
        </div>
        <div v-else>
            <filter-button></filter-button>
        </div>
    </div>
</template>

<script>
    import Contact from './Contact.vue';
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
            this.listenEvents()
        },

        data() {
            return {
                contacts: null,
                showCreateForm: true
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

            /**
             * Remove contact on client side.
             * 
             * @param  int contactId
             * @return void
             */
            removeContact(contactId) {
                this.contacts = this.contacts.filter(contact => contact.id != contactId);
            },

            /**
             * @return void
             */
            listenEvents() {
                eventDispatcher.$on('search-mode-on', contacts => {
                    this.contacts = contacts;
                    this.showCreateForm = false;
                });

                eventDispatcher.$on('search-mode-off', () => {
                    this.selectContactsFromDb();
                    this.showCreateForm = true;
                });
            }
        },

        components: { CreateContactForm, Contact }
    }
</script>