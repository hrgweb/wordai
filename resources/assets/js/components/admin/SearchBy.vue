<template>
    <div class="Search">
        <div class="search-by Filter">
            <!-- Controls -->
            <div class="controls">
                <label for="searchBy">Search by</label> &nbsp;
                <select v-model="searchBy" @change="changeSearch">
                    <option value="select">SELECT</option>
                    <option v-for="search in search.by" :value="search">{{ search.toUpperCase() }}</option>
                </select>
            </div>

            <!-- Range Filter -->
            <div class="range-filter" v-if="searchBy === 'range'">
                <form method="POST">
                    <div class="from pull-left">
                        <label for="from">From</label>
                        <input type="date" id="from" v-model="form.from">
                    </div>

                    <div class="to pull-left">
                        <label for="to">To</label>
                        <input type="date" id="to" v-model="form.to">
                    </div>

                    <button type="submit" class="btn btn-primary" @click.prevent="searchArticlesByRange">Search Now</button>
                </form>
            </div>

            <!-- Input -->
            <div class="input" v-else-if="searchBy === 'user' || searchBy === 'group' || searchBy === 'website'">
                <form method="POST" @submit.prevent>
                    <input
                        type="text"
                        class="input-search"
                        id="input"
                        :placeholder="placeHolder"
                        v-model="form.input">
                        <!-- @keyup.enter="searchNow"> -->

                    <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                </form>

                <range-filter @searchNow="searchNow"></range-filter>
            </div>

            <!-- Loading -->
            <div class="misc" v-if="isLoading">
                <span>Fetching Data...</span>
            </div>

            <div class="clear"></div>
        </div>

        <!-- Result -->
        <a id="articles_search_by_range">
            <div class="Result">
                <h2 class="text-center">
                    {{ headerCaption }}
                    <span class="badge">{{ articlesCount }}</span>
                </h2>

                <!-- Table -->
                <report-table :articles="articles"></report-table>
            </div>
        </a>
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';
    import RangeFilter from './RangeFilter.vue';

    export default {
        components: { ReportTable, RangeFilter },
        data() {
            return {
                articles: [],
                articlesCount: 0,
                form: new Form({
                    from: '',
                    to: '',
                    input: ''
                }),
                search: { by: ['range', 'user', 'group', 'website'] },
                searchBy: 'select',
                isLoading: false
            }
        },
        computed: {
            placeHolder() {
                return 'Seach for ' + this.searchBy;
            },

            headerCaption() {
                let text = '';

                if (this.searchBy === 'select') {
                    text = 'Please select what to search';
                } else if (this.searchBy === 'range') {
                    text = 'Search by range data';
                } else {
                    text = 'Search by ' + this.searchBy + ': `' + this.form.input + '`';
                }

                return text;
            }
        },
        watch: {
            articles(data) {
                this.articlesCount = data.length;
            }
        },
        methods: {
            initSetupDate() {
                let dateObj = new Date();
                let month = dateObj.getMonth() + 1; //months from 1-12
                month = parseInt(month, 10) > 9 ? month : '0' + month;
                let day = dateObj.getDate();
                day = parseInt(day, 10) > 9 ? day : '0' + day;
                let year = dateObj.getFullYear();

                this.form['from'] = year + "-" + month + "-" + day;
                this.form['to'] = year + "-" + month + "-" + day;
            },

            searchArticlesByRange() {
                this.form.post('/admin/searchArticlesByRange')
                    .then(data => this.articles = data);
            },

            setArticlesData(articles) {
                this.isLoading = false;
                this.articles = articles;
            },

            searchNow(data) {
                this.form['from'] = data.from;
                this.form['to'] = data.to;

                if (this.form.input.length > 0) {
                    this.isLoading = true;

                    if (this.searchBy === 'user') {
                        this.form.post('/admin/searchByUser').then(data => this.setArticlesData(data));
                    } else if (this.searchBy === 'group') {
                        this.form.post('/admin/searchByGroup').then(data => this.setArticlesData(data));
                    } else if (this.searchBy === 'website') {
                        this.form.post('/admin/searchByWebsite').then(data => this.setArticlesData(data));
                    }
                } else {
                    new Noty({
                        type: 'error',
                        text: `Please enter your search in the input field.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
                }
            },

            changeSearch() {
                this.form.reset();
                this.initSetupDate();
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