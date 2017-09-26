<template>
    <div class="Search">
        <!-- Article Editor -->
        <article-editor
            :article="article"
            :peditoraccess="hasPeditorAccess"
            v-if="isEdit"
            @isUpdated="updateRecord"
            @isDismiss="dismissUpdate">
        </article-editor>


        <div class="admin-search" v-show="! isEdit">
            <div class="search-by Filter">
                <!-- Controls -->
                <div class="controls">
                    <label for="searchBy">Search by</label> &nbsp;
                    <select v-model="report.searchBy" @change="report.changeSearch">
                        <option value="select">SELECT</option>
                        <option v-for="search in search.by" :value="search">{{ search.toUpperCase() }}</option>
                    </select>
                </div>

                <!-- Range Filter -->
                <div class="range-filter" v-if="report.searchBy === 'range'">
                    <form method="POST">
                        <div class="from pull-left">
                            <label for="from">From</label>
                            <input type="date" id="from" v-model="report.form.from">
                        </div>

                        <div class="to pull-left">
                            <label for="to">To</label>
                            <input type="date" id="to" v-model="report.form.to">
                        </div>

                        <button type="submit" class="btn btn-primary" @click.prevent="report.searchArticlesByRange">Search Now</button>
                    </form>
                </div>

                <!-- Input -->
                <div class="input" v-else-if="report.searchBy === 'user' || report.searchBy === 'group' || report.searchBy === 'website'">
                    <form method="POST" @submit.prevent>
                        <input
                            type="text"
                            class="input-search"
                            id="input"
                            :placeholder="report.placeHolder"
                            v-model="report.form.input">
                            <!-- @keyup.enter="searchNow"> -->

                        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                    </form>

                    <range-filter @searchNow="report.searchNow"></range-filter>
                </div>

                <!-- Loading -->
                <div class="misc" v-if="report.isLoading">
                    <span>Fetching Data...</span>
                </div>

                <div class="clear"></div>
            </div>

            <!-- Result -->
            <div class="Result">
                <h2 class="text-center">
                    {{ report.headerCaption }}
                    <span class="badge">{{ report.searchByArticlesCount }}</span>
                </h2>

                <!-- Table -->
                <report-table
                    :articles="report.searchByArticlesData"
                    tableType="admin-search-by">
                </report-table>
            </div>
        </div>
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';
    import RangeFilter from './RangeFilter.vue';
    import ArticleEditor from './../editor/ArticleEditor.vue';
    import { ReportingBus } from './../../eventbus/ReportingBus.js';
    import { ArticleEditorMixin } from './../../mixins/ArticleEditorMixin.js';

    export default {
        props: ['user'],
        components: { ArticleEditor, ReportTable, RangeFilter },
        mixins: [ ArticleEditorMixin ],
        data() {
            return {
                search: { by: ['range', 'user', 'group', 'website'] },
                report: ReportingBus
            }
        }
    }
</script>

<style scoped>
    .Search .ArticleEditor {
        background: #fff;
        padding-top: 1em;
        padding: 1em 1em .1em;
    }

    .controls {
        width: 170px;
        float: left;
        /*margin-right: 1em;*/
    }

    .misc {
        color: #fff;
        font-size: 1.3em;
        text-transform: uppercase;
        margin-top: 0.3em;
        margin-left: 1em;
    }

    .search-by,
    .input { display: flex; }
</style>