<template>
    <div class="Edited" style="top: 3em;">
        <div class="articles-edited-this-week">
            <h2>
                Articles Edited
                <span class="badge">{{ articlesCount }}</span>
            </h2>
            <!-- <p>
                <span>From: <b>{{ fromUtc }}</b></span> -
                <span>To <b>{{ toUtc }}</b></span>
            </p> -->

            <!-- Report Table -->
            <report-table
                :articles="articles">
            </report-table>
        </div>
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';

    export default {
        props: ['articles', 'fromUtc', 'toUtc'],
        components: { ReportTable },
        data() {
            return {
                articlesCount: 0,
                time: moment
            }
        },
        watch: {
            articles() {
                this.articlesCount = this.articles.length;
            }
        }
    }
</script>