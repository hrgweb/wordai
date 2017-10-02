<template>
    <div class="AdminApprove">
        <!-- Disapprove component -->
        <disapprove-article
            :article="article"
            v-if="showDisapprovePanel"
            @onSuccessSubmit="onSuccessSubmit"
            @isCancel="isCancelDisapprove">
        </disapprove-article>

        <div class="Approve">
            <h2>
                Articles To Approve
                <span class="badge">{{ articlesCount }}</span>
            </h2>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Writer</th>
                        <th>Title</th>
                        <th>Domain</th>
                        <th>Keyword</th>
                        <th>Date Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(article, index) in articles">
                        <td>{{ article.firstname }} {{ article.lastname }}</td>
                        <td>{{ article.doc_title }}</td>
                        <td>{{ article.domain }}</td>
                        <td>{{ article.keyword }}</td>
                        <td>{{ time(article.created_at).format('ll') }}</td>
                        <td>
                            <button type="button" class="btn btn-success" @click="approveArticle(article, index)">Approve</button>
                            <button type="button" class="btn btn-danger" @click="disApproveArticle(article, index)">Disapprove</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="Pagination">
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
    </div>
</template>

<script>
    import DisapproveArticle from './DisapproveArticle.vue';
    import { EditorPaginationMixin } from './../../mixins/EditorPaginationMixin.js';

    export default {
        components: { DisapproveArticle },
        mixins: [ EditorPaginationMixin ],
        data() {
            return {
                time: moment,
                articlesToApprove: [],
                article: { data: {}, index: 0},
                showDisapprovePanel: false
            };
        },
        created() {
            this.toApproveArticles('/admin/toApproveArticles'+this.pagePath);
        },
        methods: {
            toApproveArticles(url) {
                axios.get(url).then(response => {
                    let payload = response.data;

                    this.articles = this.editor.mapResultOfArticles(payload.data);
                    this.pageCount = payload.last_page;
                    this.urlPath = payload.path;
                    this.isLoading = false;
                });
            },

            approveArticle(article, index) {
                let title = article.doc_title;

                axios.post('/admin/approveArticle', article).then(response => {
                    // remove item after article has approved
                    response.data && this.articles.splice(index, 1);

                    // notify user
                    new Noty({
                        type: 'info',
                        text: `<b>${title}</b> article successfully approved.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
                });
            },

            disApproveArticle(article, index) {
                // set article data and show panel
                this.showDisapprovePanel = true;
                this.article = {
                    data: article,
                    index: index
                };
            },

            isCancelDisapprove() {
                this.showDisapprovePanel = false;
            },

            onSuccessSubmit() {
                this.showDisapprovePanel = false;
                this.articles.splice(this.article.index, 1);
            }
        }
    }
</script>

<style scoped>
    h2 {
        padding: 1em 0 0.5em 0.5em;
        color: #fff;
    }

    button { width: 90px; }
    table tbody td:first-child {
        width: 100px;
    }

    table tbody td:nth-child(6) {
        width: 200px !important;
    }

    .Pagination { margin-left: 1em; }
    .Pagination ul { margin: 0 0 1em; }
</style>