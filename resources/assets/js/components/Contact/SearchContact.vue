<template>
    <form class="navbar-form navbar-left" role="search" @submit.prevent="search">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Search contacts" v-model="contactToSearch">
        </div>
        <button type="submit" class="btn btn-default">Find</button>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                contactToSearch: ''
            }
        },

        /**
         * Mounted event of component.
         * 
         * @return void
         */
        mounted() {
            eventDispatcher.$on('search-mode-off', () => {
                this.contactToSearch = '';
            });
        },

        methods: {
            /**
             * Search contacts by name.
             * 
             * @return void
             */
            search() {
                if (!this.contactToSearch) {
                    return false;
                } 
                
                axios.get('/api/contacts/search/' + this.contactToSearch)
                    .then(response => {
                        eventDispatcher.$emit('search-mode-on', response.data.data);
                    })
                    .catch(error => {
                        //
                    });
            }
        }
    }
</script>