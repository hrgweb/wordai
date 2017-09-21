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
        this.listenWhenPowerEditorUpdated();
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
        listenWhenPowerEditorUpdated(data) {
            ArticleBus.$on('editorUpdatedSpintaxCopy', data => {
                this.articles[this.index].spintax_copy = data.spintax;
            });
        },

        updateArticle(data) {
            this.isEdit = false;
            this.article = data.article;
            this.index = data.index;
            this.tableType = data.tableType;
            Vue.nextTick(() => this.isEdit = true );
        },

        setupToUpdateRecord(data) {
            switch(this.tableType) {
                case 'article-to-edit':
                    this.listToEdit[this.index].spin = data.article;

                    if (data.times.length > 0) {
                        this.listToEdit[this.index].hr_spent_editor_edit_article = data.times[0];
                        this.listToEdit[this.index].min_spent_editor_edit_article = data.times[1];
                        this.listToEdit[this.index].sec_spent_editor_edit_article = data.times[2];
                    }
                    break;
                case 'article-edited':
                    this.listEditedArticles[this.index].spin = data.article;

                    if (data.times.length > 0) {
                        this.listEditedArticles[this.index].hr_spent_editor_edit_article = data.times[0];
                        this.listEditedArticles[this.index].min_spent_editor_edit_article = data.times[1];
                        this.listEditedArticles[this.index].sec_spent_editor_edit_article = data.times[2];
                    }
                    break;
                default:
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
            if (this.tableType !== undefined) {
                this.setupToUpdateRecord(payload);
            } else {
                window.location.href = '/admin'; // from admin
            }
        },

        updateArticleData() {
            ArticleBus.$on('isRespinArticle', data => {
                this.setupToUpdateRecord(data);
                // this.articles[this.index].spin = data.spin;
            });
        },
    }
};