<template>
    <div class="Search">
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
        <!-- <a id="articles_search_by_range"> -->
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
        <!-- </a> -->
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';
    import RangeFilter from './RangeFilter.vue';
    import { ReportingBus } from './../../eventbus/ReportingBus.js';

    export default {
        components: { ReportTable, RangeFilter },
        data() {
            return {
                search: { by: ['range', 'user', 'group', 'website'] },
                report: ReportingBus
            }
        }
    }
</script>

<style scoped>
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