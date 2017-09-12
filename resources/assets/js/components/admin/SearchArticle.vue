<template>
    <div class="Search">
        <div class="Search__component">
            <!-- Article Editor -->
            <article-editor
                :article="article"
                :peditoraccess="hasPeditorAccess"
                v-if="isEdit"
                @isUpdated="updateRecord"
                @isDismiss="dismissUpdate">
            </article-editor>
        </div>

        <div class="Search__area">
            <div class="header">
                <!-- Report Header -->
                <report-header :count="articleBus.noOfAllArticles">
                    <template slot="head">Search Article</template>
                </report-header>
            </div>

            <div class="search-input">
                <input type="text" class="form-control" v-model="search">
            </div>

            <div class="content">
                <!-- <div v-if="isGroupByEqualSelect"> -->
                <div>
                    <report-table
                        :articles="articleBus.articles"
                        @isEditing="updateArticle">
                    </report-table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ArticleEditor from './../editor/ArticleEditor.vue';
    import ReportHeader from './ReportHeader.vue';
    import ReportTable from './ReportTable.vue';
    import { UserArticleMixin } from './../../mixins/UserArticleMixin.js';

    export default {
        components: { ArticleEditor, ReportHeader, ReportTable },
        mixins: [ UserArticleMixin ],
        data() {
            return {
                search: '',
                articleBus: ArticleBus,
                isEdit: false,
            }
        },
        mounted() {
            this.hideParagraphFromAndTo();
        },
        methods: {
            hideParagraphFromAndTo() {
                $('div.ReportHeader').find('p').hide();
            },

            updateArticle(data) {
                this.isEdit = false;
                this.article = data.article;
                this.index = data.index;
                Vue.nextTick(() => this.isEdit = true );
            },
        }
    }
</script>

<style scoped>
    .search-input > input {
        padding: 1.5em 1em;
        font-size: 1.5em;
        margin-bottom: 3em;
    }
</style>