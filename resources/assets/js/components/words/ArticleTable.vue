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
                index: 0,
            }
        },
        methods: {
            editArticle(article, index) {
                this.index = index;

                axios.get('/user/editArticle?wordId='+article.id).then(response => {
                    let data = response.data;

                    // check if article is approve
                    if (data.isArticleApprove === 1) {
                        this.btnStateIfArticleIsProcess('Approved', '#6CDA6C');
                    } else {
                        ArticleBus.$emit('isEdit', {
                            data: data,
                            index: index
                        });
                    }

                    // check if spintax is empty & isProcess is 0
                    /*if (data.isProcess === 0) {
                        this.isProcess = false;
                        this.$emit('isEdit', {
                            data: data,
                            index: index
                        });
                    } else {
                        this.isProcess = true;

                        // notify user that article has already process e.g isProcess=1
                        if (this.isProcess) {
                            // show waiting for edit button
                            this.btnStateIfArticleIsProcess();

                            let articleTitle = data.doc_title;
                            new Noty({
                                type: 'error',
                                text: `<b>${articleTitle}</b> article already processed. You can't process and article that is done already.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();
                        }
                    }*/
                });
            },

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