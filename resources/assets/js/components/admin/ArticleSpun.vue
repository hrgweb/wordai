<template>
    <div class="Spun" style="top: 5em;">
        <!-- Article Editor -->
        <article-editor
            :article="article"
            :peditoraccess="hasPeditorAccess"
            v-if="isEdit"
            @isUpdated="updateRecord"
            @isDismiss="dismissUpdate">
        </article-editor>

        <div class="articles-spun" v-if="! isEdit">
            <div class="header">
                <!-- Report Header -->
                <report-header :count="report.noOfArticlesSpunThisWeek">
                    <template slot="head">Articles Spun</template>
                </report-header>
            </div>
            <div class="content">
                <!-- <div v-if="isGroupByEqualSelect"> -->
                <div>
                    <report-table
                        :articles="report.spunThisWeek"
                        tableType="admin-spun-article">
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
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';
    import ReportHeader from './ReportHeader.vue';
    import ArticleEditor from './../editor/ArticleEditor.vue';
    import { ArticleEditorMixin } from './../../mixins/ArticleEditorMixin.js';

    export default {
        props: ['user'],
        components: { ReportTable, ReportHeader, ArticleEditor },
        mixins: [ ArticleEditorMixin ],
        data() {
            return {
                report: ReportingBus
            }
        }
    }
</script>