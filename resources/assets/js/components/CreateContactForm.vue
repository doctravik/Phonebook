<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Create contact</h3>
        </div>

        <div class="panel-body">
            <form @submit.prevent="createContact()">
                <div class="form-group" :class="{'has-error': errors.has('name')}">
                    <label for="name" class="control-label">Name</label>

                    <input type="name" class="form-control" name="name" v-model="form.name" required autofocus>
                    <span class="help-block" v-if="errors.has('name')">
                        <strong v-text="errors.get('name')"></strong>
                    </span>
                </div>

                <div class="form-group" :class="{'has-error': errors.has('phone_number')}">
                    <label for="name" class="control-label">Phone</label>

                    <input type="name" class="form-control" name="name" v-model="form.phone_number" required>
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
    import Errors from './../helpers/Errors.js';

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
    }
</script>