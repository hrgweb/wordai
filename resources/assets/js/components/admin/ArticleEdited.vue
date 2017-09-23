<template>
    <div class="Edited" style="top: 3em;">
        <a id="articles_edited_this_week">
            <div class="articles-edited-this-week">
                <div class="header">
                    <!-- Report Header -->
                    <report-header :count="report.noOfArticlesEditedThisWeek">
                        <template slot="head">Articles Edited This Week</template>
                    </report-header>
                </div>
                <div class="content">
                    <!-- <div v-if="isGroupByEqualSelect"> -->
                    <div>
                        <report-table
                            :articles="report.editedThisWeek"
                            tableType="admin-article-edited">
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
    import { ArticleEditorMixin } from './../../mixins/ArticleEditorMixin.js';

    export default {
        components: { ReportTable, ReportHeader },
        data() {
            return {
                report: ReportingBus
            }
        }
    }
</script>