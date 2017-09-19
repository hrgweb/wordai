<template>
    <div class="RangeFilter">
        <!-- Filter -->
        <div class="Filter">
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

        <a id="articles_search_by_range">
            <!-- Result -->
            <div class="Result">
                <h2 class="text-center">
                    Search Articles By Date Range
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

    export default {
        components: { ReportTable },
        data() {
            return {
                articles: [],
                articlesCount: 0,
                form: new Form({
                    from: '',
                    to: ''
                })
            }
        },
        watch: {
            articles(data) {
                this.articlesCount = data.length;
            }
        },
        mounted() {
            this.initSetupDate();
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
            }
        }
    }
</script>