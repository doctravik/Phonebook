<template>
    <li class="media">
        <div class="media__left">
            <img class="media__image" src="http://placehold.it/64x64">
        </div>
        <div class="media__body">
            <div v-if="editing" class="form-group is-marginless" :class="{'has-error': errors.has('name')}">
                <input type="text" name="name" class="form-control" 
                    v-model="name" @keyup.enter="updateContact" required>
                <span class="help-block is-marginless" v-if="errors.has('name')">
                    <strong v-text="errors.get('name')"></strong>
                </span>
            </div>
            <div v-else>
                <a href="#" @click.prevent="togglePhonesMenu"><strong v-text="name"></strong></a>
            </div>
        </div>
        <div class="media__right">
            <div v-if="editing">
                <span class="btn btn-success" @click="updateContact">Update</span>
                <span class="btn btn-default" @click="cancelEditing">Cancel</span>
            </div>
            <div v-else>
                <span class="btn btn-success" @click="editContact">Edit</span>
                <span class="btn btn-danger" @click="deleteContact">Delete</span>
            </div>
        </div>
        <phones :contact="contact" v-show="showPhonesMenu"></phones>
    </li>
</template>

<script>
    import Errors from './../../helpers/Errors.js';

    export default {
        props: ['contact'],

        /**
         * Mounted event of component.
         * 
         * @return void
         */
        mounted() {
            this.listenEvents();
        },

        data() {
            return {
                name: this.contact.name,
                editing: false,
                errors: new Errors(),
                showPhonesMenu: false
            }
        },

        methods: {
            /**
             * @return void
             */
            editContact() {
                this.editing = true;
            },

            /**
             * @return void
             */
            cancelEditing() {
                this.editing = false;
                this.resetForm();
            },

            /**
             * @return void
             */
            updateContact() {
                axios.put('/api/contacts/' + this.contact.id, { name: this.name })
                    .then(response => {
                        this.editing = false;
                        this.contact.name = this.name;
                        this.resetForm();
                    })
                    .catch(error => {
                        this.errors.record(error.response.data);
                    });

            },

            /**
             * Remove contact on server side.
             * 
             * @return void
             */
            deleteContact() {
                axios.delete('/api/contacts/' + this.contact.id)
                    .then(response => {
                        this.$emit('remove-contact', this.contact.id);
                    })
                    .catch(error => {
                        //
                    })
            },

            /**
             * Reset form value.
             * 
             * @return void
             */
            resetForm() {
                this.errors.clear();
                this.name = this.contact.name;
            },

            /**
             * @return void
             */
            togglePhonesMenu() {
                this.showPhonesMenu = !this.showPhonesMenu;
                eventDispatcher.$emit('hide-phones', this.contact.id);
            },

            /**
             * @return void
             */
            listenEvents() {
                eventDispatcher.$on('hide-phones', contactId => {
                    if (this.contact.id != contactId) {
                        this.showPhonesMenu = false;
                    }
                });
            }
        }
    }
</script>