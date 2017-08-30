<template>
    <div class="groupByUser">
        <h3>
            {{ creator.full_name }}
            <span class="badge">{{ count }}</span>
        </h3>

        <!-- Report Table -->
        <report-table :articles="articles"></report-table>
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';

    export default {
        props: ['creator'],
        components: { ReportTable },
        data() {
            return {
                report: ReportingBus,
                articles: [],
                count: 0
            };
        },
        watch: {
            articles(data) {
                this.count = data.length;
            }
        },
        mounted() {
            this.listOfArticlesCreatedByUser();
        },
        methods: {
            listOfArticlesCreatedByUser() {
                let user_id = this.creator.user_id;
                let params = this.report.paramsForDate();
                params = params + '&user_id='+user_id;

                axios.get('/admin/listOfArticlesCreatedByUser'+params).then(response => this.articles= response.data);
            }
        }
    }
</script>