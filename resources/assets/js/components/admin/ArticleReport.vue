<template>
    <div class="Report">
        <!-- New Articles This Week -->
        <div class="articles-this-week">
            <div class="header">
                <!-- Report Header -->
                <report-header :count="report.noOfArticlesThisWeek">
                    <template slot="head">Articles This Week</template>
                </report-header>
            </div>
            <div class="content">
                <h3>{{ filterGroupBy }}</h3>

                <!-- Report Table -->
                <report-table :articles="report.articles"></report-table>
            </div>
        </div>
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';
    import ReportHeader from './ReportHeader.vue';

    export default {
        components: { ReportTable, ReportHeader },
        data() {
            return {
                report: ReportingBus
            }
        },
        computed: {
            filterGroupBy() {
                return this.report.reportingFilter.groupBy.toUpperCase();
            }
        }
    }
</script>