<template>
    <div class="Editor">
        <!-- Article Editor -->
        <article-editor
            :article="article"
            :peditoraccess="hasPeditorAccess"
            v-if="isEdit"
            @isUpdated="updateRecord"
            @isDismiss="dismissUpdate">
        </article-editor>

        <!-- <div class="Editor__table" v-if="! isEdit"> -->
        <div class="Editor__table">
            <!-- Report Header -->
            <report-header :count="noOfAllArticles">
                <template slot="head">List of Articles</template>
            </report-header>

            <div class="search-input">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search for the article title and hit enter"
                    v-model="search"
                    @keyup.enter="searchArticle">
            </div>

            <!-- Loading -->
            <div class="Loading" v-if="isLoading">
                <br><br><p class="text-center">FETCHING DATA...</p>
            </div>

            <!-- Result -->
            <div class="Result" v-else>
                <div class="search-result">
                    <article-result
                        :articles="articles"
                        v-if="isArticlesNotEmpty"
                        @isEditing="updateArticle">
                    </article-result>
                </div>

                <div class="Pagination" v-if="! isSearch">
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
    </div>
</template>

<script>
    import ArticleResult from './ArticleResult.vue';
    import ArticleEditor from './../editor/ArticleEditor.vue';
    import ReportHeader from './ReportHeader.vue';
    import Paginate from 'vuejs-paginate'
    import { UserArticleMixin } from './../../mixins/UserArticleMixin.js';
    import { EditorPaginationMixin } from './../../mixins/EditorPaginationMixin.js';

    export default {
        props: ['user'],
        components: {
            ArticleResult,
            ArticleEditor,
            ReportHeader,
            Paginate
        },
        mixins: [ UserArticleMixin, EditorPaginationMixin ],
        data() {
            return {
                isEdit: false,
                articlesCount: 0,
                error: '',
                authUser: {},
                hasPeditorAccess: false,
                listToEdit: [],
                listEditedArticles: [],
                listArticleToPublish: [],
                articleBus: ArticleBus,
                search: '',
                noOfAllArticles: 0,
                isSearch: false,
                isLoading: true
            };
        },
        watch: {
            articles(data) {
                this.noOfAllArticles = data.length;
                this.isLoading = false;

                return data;
            },

            authUser(data) {
                // has power editor access
                if (data.has_peditor_access === 0) {
                    this.hasPeditorAccess = false;
                    this.error = 'You have no access to power editor feature.';
                } else {
                    this.hasPeditorAccess = true;
                }
            }
        },
        created() {
            this.allArticles('/admin/allArticles'+this.pagePath);
        },
        mounted() {
            this.authUser = JSON.parse(this.user);
            this.hideParagraphFromAndTo();
            this.listenWhenPowerEditorUpdated();
            this.updateArticleData();

            // Bus
            let vm = this;
            ArticleBus.$on('isEditing', data => vm.updateArticle(data));
        },
        methods: {
            allArticles(url) {
                axios.get(url).then(response => {
                    let payload = response.data;

                    this.articles = this.editor.mapResultOfArticles(payload.data);
                    this.pageCount = payload.last_page;
                    this.urlPath = payload.path;
                    this.isLoading = false;
                });
            },

            updateArticle(data) {
                this.isEdit = false;
                this.article = data.article;
                this.index = data.index;
                Vue.nextTick(() => this.isEdit = true );
            },

            updateRecord(data) {
                if (data) {
                    // this.isEdit = false;  // close the article-editor component
                    this.articles[this.index].spin = data.article;
                    this.articles[this.index].hr_spent_editor_edit_article = data.times[0];
                    this.articles[this.index].min_spent_editor_edit_article = data.times[1];
                    this.articles[this.index].sec_spent_editor_edit_article = data.times[2];

                    // successfully updated
                    let articleTitle = this.article.doc_title;
                    new Noty({
                        type: 'info',
                        text: `<b>${articleTitle}</b> article successfully updated.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
                }
            },

            dismissUpdate(payload) {
                this.isEdit = false;
                this.articles[this.index].hr_spent_editor_edit_article = payload[0];
                this.articles[this.index].min_spent_editor_edit_article = payload[1];
                this.articles[this.index].sec_spent_editor_edit_article = payload[2];
            },

            listenWhenPowerEditorUpdated(data) {
                ArticleBus.$on('editorUpdatedSpintaxCopy', data => {
                    this.articles[this.index].spintax_copy = data.spintax;
                });
            },

            updateArticleData() {
                ArticleBus.$on('isRespinArticle', data => {
                    this.articles[this.index].spin = data.spin;
                });
            },

            articlesToEdit(data) {
                this.listToEdit = data.filter(item => {
                    return (
                        parseInt(item.hr_spent_editor_edit_article, 10) <= 0 &&
                        parseInt(item.min_spent_editor_edit_article, 10) <= 0 &&
                        parseInt(item.sec_spent_editor_edit_article, 10) <= 0
                    );
                });
            },

            editedArticles(data) {
                this.listEditedArticles = data.filter(item => {
                    return (
                        parseInt(item.hr_spent_editor_edit_article, 10) > 0 ||
                        parseInt(item.min_spent_editor_edit_article, 10) > 0 ||
                        parseInt(item.sec_spent_editor_edit_article, 10) > 0
                    );
                });
            },

            articlesToPublish(data) {
                this.listArticleToPublish = data.filter(item => {
                    return item.isEditorEdit === 1;
                });
            },

            hideParagraphFromAndTo() {
                $('div.ReportHeader').find('p').hide();
            },

            searchArticle() {
                this.isSearch = true;
                this.isLoading = true;

                if (this.search.length > 0) {
                    let search = this.search.trim();

                    axios.get('/admin/searchArticle?search='+search)
                        .then(response => this.articles = this.editor.mapResultOfArticles(response.data));
                } else {
                    this.allArticles(this.pagePath);
                    this.isSearch = false;
                }
            }
        }
    }
</script>

<style scoped>
    .Editor { padding: 0; }
    .Copyscape { margin-top: 5em; }

    .search-input > input {
        padding: 1.5em 1em;
        font-size: 1.5em;
    }

    .Loading p { font-size: 2em; }
</style>