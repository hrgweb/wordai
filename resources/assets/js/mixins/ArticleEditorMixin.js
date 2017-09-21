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
        ReportingBus.$on('isLoadedListToEdit', data => this.listToEdit = data);
        ReportingBus.$on('isLoadedListEditedArticles', data => this.listEditedArticles = data);
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

        updateRecord(data) {
            if (data) {
                this.setupToUpdateRecord(data);

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