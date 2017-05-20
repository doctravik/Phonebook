class Errors {
    /**
     * @return void
     */
    constructor() {
        this.errors = {};
    }


    /**
     * Determine if an errors exists for the given field.
     *
     * @param string field
     * @return boolean
     */
    has(field) {
        return this.errors.hasOwnProperty(field);
    }


    /**
     * Determine if we have any errors.
     *
     * @return boolean
     */
    any() {
        return Object.keys(this.errors).length > 0;
    }


    /**
     * Retrieve the error message for a field.
     *
     * @param string field
     * @return string
     */
    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }


    /**
     * Record the new errors.
     *
     * @param object errors
     * @return void
     */
    record(errors) {
        this.errors = errors;
    }


    /**
     * Clear one or all error fields.
     *
     * @param string|null} field
     * @return void
     */
    clear(field) {
        if (field) {
            delete this.errors[field];

            return;
        }

        this.errors = {};
    }
}

export default Errors;