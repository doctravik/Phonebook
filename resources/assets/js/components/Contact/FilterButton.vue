<template>
    <div class="filter-button">
        <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" @click="cancelFiltering">
            <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear filtering
        </button>
    </div>
</template>

<script>
    export default {
        methods: {
            /**
             * @return void
             */
            cancelFiltering() {
                eventDispatcher.$emit('search-mode-off');
            }
        }
    }
</script>

<style scoped>
    .filter-button {
        background-color: whitesmoke;
        border: 1px solid #ddd;
        border-radius: 4px;
        height: 132px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>