<template>
    <div class="Waiting" style="top: 5em;">
        <div class="articles-to-be-edit">
            <h2>
                Articles Waiting To Be Edit
                <span class="badge">{{ articlesCount }}</span>
            </h2>
           <!--  <p>
               <span>From: <b>{{ fromUtc }}</b></span> -
               <span>To <b>{{ toUtc }}</b></span>
           </p> -->

            <!-- Report Table -->
            <report-table
                :articles="articles"
                :isPublish="false">
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
            articles(data) {
                this.articlesCount = this.articles.length;
            }
        }
    }
</script>