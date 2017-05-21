<template>
    <form class="phone">
        <div class="phone__label">
            <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>            
        </div>
        <div class="phone__content" :class="{'has-error': errors.has('phone_number')}">
            <div class="phone__number" :class="{'has-error': errors.has('phone_number')}">
                <input type="text" class="form-control" v-model="phoneNumber">
            </div>
            <div class="phone__buttons">
                <div class="btn-toolbar" role="toolbar"> 
                    <button type="button" class="btn btn-default" @click="createPhone">
                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-default" @click="cancelCreating">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </button> 
                </div>
            </div>
            <span class="help-block phone__error" v-if="errors.has('phone_number')">
                <strong v-text="errors.get('phone_number')"></strong>
            </span>
        </div>
    </form>
</template>

<script>
    import Form from './Form.js';

    export default {
        props: ['contact'],

        data() {
            return {
                phoneNumber: null
            }
        },

        methods: {
            /**
             * Store contact in db.
             * 
             * @return void
             */
            createPhone() {
                axios.post('/api/contacts/' + this.contact.id + '/phones', this.form())
                    .then(response => {
                        this.$emit('add-phone', response.data.data);
                        this.resetForm();
                    })
                    .catch(error => {
                        this.errors.record(error.response.data);
                    });
            },

            /**
             * @return void
             */
            cancelCreating() {
                this.$emit('cancel-creating');
            },

            /**
             * Reset form value.
             * 
             * @return void
             */
            resetForm() {
                this.errors.clear();
                this.phoneNumber = null;
            },
        },

        mixins: [ Form ]
    }
</script>