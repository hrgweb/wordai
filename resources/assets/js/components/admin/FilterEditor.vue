<template>
    <div class="Filter">
        <div class="order-by">
            <label for="orderBy">Order by: </label> &nbsp;
            <select v-model="editorFilter.orderBy" @change="orderByChange">
                <option v-for="order in orderBy" :value="order">{{ order.toUpperCase() }}</option>
            </select>
        </div>

        <div class="sort-by">
            <label for="sortBy">Sort by: </label> &nbsp;
            <select v-model="editorFilter.sortBy" @change="sortByChange">
                <option v-for="sort in sortBy" :value="sort">{{ sort.toUpperCase() }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                orderBy: ['created_at', 'domain', 'writer'],
                sortBy: ['asc', 'desc'],
                editorFilter: {
                    orderBy: 'created_at',
                    sortBy: 'asc'
                }
            };
        },
        methods: {
            orderByChange() {
                this.$emit('hasChangeGroupBy', { filter: this.editorFilter });
            },

            sortByChange() {
                this.$emit('hasChangeOrderBy', { filter: this.editorFilter });
            }
        }
    }
</script>

<style scoped>
    .Filter {
        background: #444444;
        color: #fff;
        padding: 1em 1em 0.7em;
        margin-top: 2em;
    }

    .Filter select {
        color: #000000;
        padding: .5em 1em .5em 0.5em;
        border: 1px solid white;
        border-radius: 2px;
    }

    .Filter div:first-child {
        float: left;
        margin-right: 1em;
    }
</style>