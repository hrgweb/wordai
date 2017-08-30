<template>
    <div class="Filter">
        <div class="group-by">
            <label for="groupBy">Group by</label>
            <select v-model="report.reportingFilter.groupBy" @change="tmpChange">
                <option value="select">SELECT</option>
                <option v-for="group in groupBy" :value="group.toLowerCase()">{{ group.toUpperCase() }}</option>
            </select>
        </div>

        <div class="order-by">
            <label for="orderBy">Order by</label>
            <select v-model="report.reportingFilter.orderBy">
                <option v-for="order in orderBy" :value="order.toLowerCase()">{{ order.toUpperCase() }}</option>
            </select>
        </div>

        <div class="sort-by">
            <label for="groupBy">Sort by</label>
             <select v-model="report.reportingFilter.sortBy">
                <option v-for="sort in sortBy" :value="sort.toLowerCase()">{{ sort.toUpperCase() }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                groupBy: ['user', 'domain'],
                sortBy: ['asc', 'desc'],
                orderBy: ['date', 'wordcount', 'title'],
                report: ReportingBus
            }
        },
        mounted() {
            console.log(this.report);
        },
        methods: {
            tmpChange() {
                console.log(this.report.reportingFilter);
            }
        }
    }
</script>

<style scoped>
    .group-by,
    .order-by {
        float: left;
        margin-right: 1em;
    }
</style>