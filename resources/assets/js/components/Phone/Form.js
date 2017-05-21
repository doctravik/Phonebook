import Errors from './../../helpers/Errors.js';

export default {
    data() {
        return {
            phoneNumber: null,
            errors: new Errors(),
        }
    },

    methods: {
        /**
         * @return object
         */
        form() {
            return { phone_number: this.phoneNumber }
        },
    },
}