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
                <div v-if="isGroupByEqualSelect">
                    <report-table :articles="report.articles"></report-table>
                </div>

                <div v-else-if="isGroupByEqualUser">
                    <report-filter-by-user
                        v-for="creator in report.creatorOfArticles"
                        :creator="creator"
                        :filterByUser="true"
                        :key="creator.user_id">
                    </report-filter-by-user>
                </div>

                <div v-else-if="isGroupByEqualDomain">
                    <report-filter-by-user
                        v-for="creator in report.creatorOfArticles"
                        :creator="creator"
                        :filterByDomain="true"
                        :key="creator.domain_id">
                    </report-filter-by-user>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';
    import ReportHeader from './ReportHeader.vue';
    import ReportFilterByUser from './ReportFilterByUser.vue';

    export default {
        components: { ReportTable, ReportHeader, ReportFilterByUser },
        data() {
            return {
                report: ReportingBus
            }
        },
        computed: {
            filterGroupBy() {
                return this.report.reportingFilter.groupBy.toUpperCase();
            },

            isGroupByEqualSelect() {
                return (this.report.reportingFilter.groupBy === 'select') ? true : false;
            },

            isGroupByEqualUser() {
                return (this.report.reportingFilter.groupBy === 'user') ? true : false;
            },

            isGroupByEqualDomain() {
                return (this.report.reportingFilter.groupBy === 'domain') ? true : false;
            }
        }
    }
</script>