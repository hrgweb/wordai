<template>
    <div class="groupByUser">
        <h3>
            <span v-if="filterByUser">{{ creator.full_name }}</span>
            <span v-else>{{ creator.domain }}</span>
            <span class="badge">{{ count }}</span>
        </h3>

        <!-- Report Table -->
        <report-table :articles="articles"></report-table>
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';

    export default {
        props: ['creator', 'filterByUser', 'filterByDomain'],
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
            this.groupByFilter(this.creator);
        },
        methods: {
            groupByFilter(creator) {
                if (this.filterByUser && creator.user_id > 0) this.listOfArticlesCreatedByUser();
                if (this.filterByDomain && creator.domain_id > 0) this.listOfDomainByArticles();
            },

            listOfArticlesCreatedByUser() {
                let user_id = this.creator.user_id;
                let params = this.report.paramsForDate();
                params = params + '&user_id='+user_id;

                axios.get('/admin/listOfArticlesCreatedByUser'+params).then(response => this.articles= response.data);
            },

            listOfDomainByArticles() {
                let domain_id = this.creator.domain_id;
                let params = this.report.paramsForDate();
                params = params + '&domain_id='+domain_id;

                axios.get('/admin/listOfDomainByArticles'+params).then(response => this.articles= response.data);
            }
        }
    }
</script>