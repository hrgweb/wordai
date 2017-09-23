<template>
    <div class="Waiting" style="top: 5em;">
        <a id="articles_to_edit_this_week">
            <div class="articles-to-be-edit">
                <div class="header">
                    <!-- Report Header -->
                    <report-header :count="report.noOfArticlesToEditThisWeek">
                        <template slot="head">Articles To Edit This Week</template>
                    </report-header>
                </div>
                <div class="content">
                    <!-- <div v-if="isGroupByEqualSelect"> -->
                    <div>
                        <report-table
                            :articles="report.waitingToEdit"
                            tableType="admin-to-edit">
                        </report-table>
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
        </a>
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