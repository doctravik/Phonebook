<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Create contact</h3>
        </div>

        <div class="panel-body">
            <form @submit.prevent="createContact()">
                <div class="form-group" :class="{'has-error': errors.has('name')}">
                    <label for="name" class="control-label">Name</label>

                    <input type="text" class="form-control" v-model="form.name" placeholder="name" required autofocus>
                    <span class="help-block" v-if="errors.has('name')">
                        <strong v-text="errors.get('name')"></strong>
                    </span>
                </div>

                <div class="form-group" :class="{'has-error': errors.has('phone_number')}">
                    <label for="name" class="control-label">Phone</label>

                    <masked-input class="form-control" v-model="form.phone_number" 
                        mask="\+1 (111) 111-11-11" placeholder="Phone" required/>
                    <span class="help-block" v-if="errors.has('phone_number')">
                        <strong v-text="errors.get('phone_number')"></strong>
                    </span>
                </div>
                
                <button class="btn btn-info">Create</button>
                <button class="btn btn-default" v-if="errors.any()" @click="resetForm()">Reset</button>
            </form>
        </div>
    </div>
</template>

<script>
    import MaskedInput from 'vue-masked-input';
    import Errors from './../../helpers/Errors.js';
    
    export default {
        data() {
            return {
                form: {
                    name: null,
                    phone_number: null
                },
                errors: new Errors()
            }
        },

        methods: {
            /**
             * Store contact in db.
             * 
             * @return void
             */
            createContact() {
                axios.post('/api/contacts', this.form)
                    .then(response => {
                        this.$emit('add-contact', response.data.data);
                        this.resetForm();
                    })
                    .catch(error => {
                        this.errors.record(error.response.data);
                    });
            },

            /**
             * Reset form value.
             * 
             * @return void
             */
            resetForm() {
                this.errors.clear();
                this.form.name = null;
                this.form.phone_number = null;
            },
        },

        components: {MaskedInput}
    }
</script>