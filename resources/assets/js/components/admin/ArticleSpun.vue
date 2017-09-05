<template>
    <div class="Spun" style="top: 5em;">
        <div class="articles-spun">
            <div class="header">
                <!-- Report Header -->
                <report-header :count="report.noOfArticlesSpunThisWeek">
                    <template slot="head">Articles Spun This Week</template>
                </report-header>
            </div>
            <div class="content">
                <!-- <div v-if="isGroupByEqualSelect"> -->
                <div>
                    <report-table :articles="report.spunThisWeek"></report-table>
                </div>

                <!-- <div v-else-if="isGroupByEqualUser">
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
                </div> -->
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
        }
    }
</script>