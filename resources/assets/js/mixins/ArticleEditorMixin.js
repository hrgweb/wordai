import { UserArticleMixin } from './UserArticleMixin.js';
import Editor from './../class/Editor.js';

export const ArticleEditorMixin = {
    data() {
        return {
            authUser: {},
            isEdit: false,
            hasPeditorAccess: false,
            tableType: '',
            editor: new Editor()
        };
    },

    mixins: [UserArticleMixin],

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
            Vue.nextTick(() => this.isEdit = true);
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

            switch (this.tableType) {
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

        updateRecordsByIndex(records, data) {
            let index = records.findIndex(item => item.id === data.id);

            if (index !== null && index >= 0) {
                Vue.set(records, index, data);
            }
        },

        updateRecord(data) {
            // if (data) {

            // update articles
            if (this.tableType === 'article-to-edit') {
                /*=============== OLD ===============*/

                // Vue.set(this.listToEdit, this.index, data);

                // find the result object to articles edited and update the value
                // this.updateRecordsByIndex(this.listEditedArticles, data);

                /*=============== NEW ===============*/
                // push to articles edited list
                // console.log(this.listToEdit[this.index])
                // this.listEditedArticles.unshift(this.listToEdit[this.index]);

                // remove this article from list of to edit articles
                this.listToEdit.splice(this.index, 1);

                // console.log('to edit')
            } else if (this.tableType === 'article-edited') {
                /*=============== OLD ===============*/

                // Vue.set(this.listEditedArticles, this.index, data);

                // find the result object to articles edited and update the value
                // this.updateRecordsByIndex(this.listToEdit, data);

                /*=============== NEW ===============*/

                // push to articles edited list
                // console.log(this.listEditedArticles[this.index])
                // this.listArticleToPublish.unshift(this.listEditedArticles[this.index]);

                // remove this article from list of to edited articles
                this.listEditedArticles.splice(this.index, 1)

                // console.log('edited')

            }
            // }
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