<template>
    <div class="Waiting" style="top: 5em;">
        <div class="articles-to-publish">
            <h2>
                Articles To Publish
                <span class="badge">{{ articlesCount }}</span>
            </h2>
           <!--  <p>
               <span>From: <b>{{ fromUtc }}</b></span> -
               <span>To <b>{{ toUtc }}</b></span>
           </p> -->

            <!-- Report Table -->
            <report-table
                :articles="articles"
                :isPublish="true"
                tableType="article-to-publish">
            </report-table>

            <paginate
                :page-count="pageCount"
                :click-handler="paginatePage"
                :prev-text="'Prev'"
                :next-text="'Next'"
                :container-class="'pagination'">

                <span slot="prevContent">&laquo;</span>
                <span slot="nextContent">&raquo;</span>
            </paginate>
        </div>
    </div>
</template>

<script>
    import ReportTable from './ReportTable.vue';
    import Paginate from 'vuejs-paginate'
    import { EditorPaginationMixin } from './../../mixins/EditorPaginationMixin.js';


    export default {
        components: { ReportTable, Paginate },
        mixins: [ EditorPaginationMixin ],
        created() {
            this.articlesToPublish();
        },
        methods: {
            articlesToPublish(data) {
                axios.get('/editor/articlesToPublish'+this.pagePath)
                    .then(response => {
                        let payload = response.data;

                        this.articles = this.editor.mapResultOfArticles(payload.data);
                        this.pageCount = payload.last_page;
                        this.urlPath = payload.path;
                    });
            }
        }
    }
</script>