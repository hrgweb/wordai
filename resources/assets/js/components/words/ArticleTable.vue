<template>
    <div class="Result">
        <!-- Display Comment -->
        <display-comment
            :article="article"
            v-if="isShowCommentPanel"
            @closeCommentPanel="closeCommentPanel">
        </display-comment>

       <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Domain</th>
                    <th>Keyword</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(article, index) in articles">
                    <td>{{ dateTime(article.created_at).format('MMMM D, YYYY @ h:mm:ss a') }}</td>
                    <td>{{ article.doc_title }}</td>
                    <td>{{ article.domain }}</td>
                    <td>{{ article.keyword }}</td>
                    <td>
                        <div class="buttons">
                            <!-- <pre>{{ article.isProcess }} = {{ article.isArticleApprove }} ={{ article.reasonArticleNotAprrove }}</pre> -->

                            <!-- actions -->
                            <div class="button-left">
                                <button type="button" class="btn btn-info" ref="editArticle" v-if="(article.isProcess === 0 || article.reasonArticleNotAprrove !== null)" @click="editArticle(article, index)">Edit</button>
                                <button type="button" class="btn btn-warning" ref="editArticle" disabled v-else-if="article.isProcess === 1 && article.isArticleApprove === 0">Waiting For Process</button>
                                <button type="button" class="btn approve" ref="editArticle" disabled v-else-if="article.isProcess === 1 && article.isArticleApprove === 1">Approved</button>
                                <!-- <button type="submit" class="btn btn-default comment" v-else-if="article.reasonArticleNotAprrove !== null"><i class="fa fa-commenting-o"></i></button> -->
                            </div>

                            <!-- comment -->
                            <div class="button-right" v-show="article.reasonArticleNotAprrove !== null">
                                <button type="submit" class="btn btn-default comment" @click="showComment(article)">
                                    <i class="fa fa-commenting-o"></i>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import DisplayComment from './DisplayComment.vue';

    export default {
        props: ['articles', 'tableType'],
        components: { DisplayComment },
        data() {
            return {
                dateTime: moment,
                article: {},
                isShowCommentPanel: false,
            }
        },
        methods: {
            showComment(article) {
                this.article = article;
                this.isShowCommentPanel = true;
            },

            closeCommentPanel() {
                this.isShowCommentPanel = false;
            }
        }
    }
</script>

<style scoped>
    .buttons { display: flex; }

    button { width: 150px; }

    button.approve {
        background: #6CDA6C;
        color: #fff;
    }

    button.btn.comment {
        width: 50px;
        margin-left: 0.5em;
    }
</style>