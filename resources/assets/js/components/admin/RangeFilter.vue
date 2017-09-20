<template>
    <div class="RangeFilter">
        <form method="POST">
            <div class="from pull-left">
                <label for="from">From</label> &nbsp;
                <input type="date" id="from" v-model="from">
            </div>

            <div class="to pull-left">
                <label for="to">To</label> &nbsp;
                <input type="date" id="to" v-model="to">
            </div>

            <button type="submit" class="btn btn-primary" @click.prevent="searchArticlesByRange">Search Now</button>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                from: '',
                to: ''
            }
        },
        mounted() {
            this.initSetupDate();
        },
        methods: {
            initSetupDate() {
                let dateObj = new Date();
                let month = dateObj.getMonth() + 1; //months from 1-12
                month = parseInt(month, 10) > 9 ? month : '0' + month;
                let day = dateObj.getDate();
                day = parseInt(day, 10) > 9 ? day : '0' + day;
                let year = dateObj.getFullYear();

                this.from = year + "-" + month + "-" + day;
                this.to = year + "-" + month + "-" + day;
            },

            searchArticlesByRange() {
                this.$emit('searchNow', this.$data);
            }
        }
    }
</script>

<style>
    .RangeFilter {
        margin-left: 1em;
    }
</style>