import { UserArticleMixin } from './UserArticleMixin.js';

export const ArticleEditorMixin = {
    data() {
        return {
            authUser: {},
            isEdit: false,
            hasPeditorAccess: false,
            tableType: ''
        };
    },

    mixins: [ UserArticleMixin ],

    mounted() {
        this.authUser = JSON.parse(this.user);
        this.updateArticleData();

        // Bus
        let vm = this;
        ArticleBus.$on('isEditing', data => vm.updateArticle(data));
    },

    watch: {
        articles(data) {
            this.articlesCount = data.length;
            // this.articlesToEdit(data);
            // this.editedArticles(data);
            // this.articlesToPublish(data);
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

    methods: {
        updateArticle(data) {
            this.isEdit = false;
            this.article = data.article;
            this.index = data.index;
            this.tableType = data.tableType;
            Vue.nextTick(() => this.isEdit = true );
        },

        setArticleAndTime(article, spin, times) {
            article.spin = spin;

            if (times.length > 0) {
                article.hr_spent_editor_edit_article = times[0];
                article.min_spent_editor_edit_article = times[1];
                article.sec_spent_editor_edit_article = times[2];
            }
        },

        setupToUpdateRecord(data) {
            let index = 0;

            switch(this.tableType) {
                case 'article-to-edit':
                    this.setArticleAndTime(
                        this.listToEdit[this.index],
                        data.article,
                        data.times
                    );
                    break;
                case 'article-edited':
                    this.setArticleAndTime(
                        this.listEditedArticles[this.index],
                        data.article,
                        data.times
                    );
                    break;
                case 'admin-article-this-week':
                    index = --this.index;
                    this.setArticleAndTime(
                        this.report.articles[index],
                        data.article,
                        data.times
                    );
                    break;
                case 'admin-article-edited':
                    index = --this.index;
                    this.setArticleAndTime(
                        this.report.editedThisWeek[index],
                        data.article,
                        data.times
                    );
                    break;
                case 'admin-to-edit':
                    index = --this.index;
                    this.setArticleAndTime(
                        this.report.waitingToEdit[index],
                        data.article,
                        data.times
                    );
                    break;
                case 'admin-spun-article':
                    index = --this.index;
                    this.setArticleAndTime(
                        this.report.spunThisWeek[index],
                        data.article,
                        data.times
                    );
                    break;
                case 'admin-search-by':
                    index = --this.index;
                    this.setArticleAndTime(
                        this.report.searchByArticlesData[index],
                        data.article,
                        data.times
                    );
                    break;
            };
        },

        updateRecord(data) {
            if (data) {
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
            this.setupToUpdateRecord(payload);
        },

        updateArticleData() {
            ArticleBus.$on('isRespinArticle', data => {
                this.setupToUpdateRecord(data);
                // this.articles[this.index].spin = data.spin;
            });
        },
    }
};