<template>
    <div class="AdminApprove">
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
</template>

<script>
    import { EditorPaginationMixin } from './../../mixins/EditorPaginationMixin.js';

    export default {
        mixins: [ EditorPaginationMixin ],
        data() {
            return {
                time: moment,
                articlesToApprove: []
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
                console.log(article, index)
            },

            disApproveArticle(article, index) {
                console.log(article, index)
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