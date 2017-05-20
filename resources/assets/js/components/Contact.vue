<template>
    <li class="media">
        <div class="media__left">
            <img class="media__image" src="http://placehold.it/64x64">
        </div>
        <div class="media__body">
            <a href="#"><strong>{{ contact.name }}</strong></a>
        </div>
        <div class="media__right">
            <span class="btn btn-success" @click="updateContact">Edit</span>
            <span class="btn btn-default" @click="deleteContact">Delete</span>
        </div>
    </li>
</template>

<script>
    export default {
        props: ['contact'],

        data() {
            return {
                name: this.contact.name,
                editing: false
            }
        },

        methods: {
            updateContact() {

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
        }
    }
</script>