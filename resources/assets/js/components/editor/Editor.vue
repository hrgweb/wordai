<template>
	<div class="Editor">
		<!-- Permission Details -->
		<div class="Permission">
			<error
				:list="error"
				:type="false"
				v-if="! hasPeditorAccess">
 			</error>
		</div>

		<!-- Article Editor -->
		<article-editor
			:article="article"
			:peditoraccess="hasPeditorAccess"
			v-if="isEdit"
			@isUpdated="updateRecord"
			@isDismiss="dismissUpdate">
		</article-editor>

		<div class="Editor__table" v-show="! isEdit">
            <!-- Article To Edit -->
            <article-to-edit
                :articles="listToEdit"
                :paginationPath="paginationPath">
            </article-to-edit>

            <!-- Article Edited -->
            <article-edited
                :articles="listEditedArticles">
            </article-edited>

            <!-- Article To Publish -->
            <article-to-publish
                :articles="listArticleToPublish">
            </article-to-publish>

            <!-- <h2>Article List <span class="badge">{{ articlesCount }}</span></h2> -->

			<!-- Article Result -->
			<!-- <article-result
				:articles="articles"
				v-if="isArticlesNotEmpty"
				@isEditing="updateArticle">
	 		</article-result> -->
		</div>
	</div>
</template>

<script>
	import ArticleResult from './ArticleResult.vue';
    import ArticleEditor from './ArticleEditor.vue';
    import ArticleToEdit from './ArticleToEdit.vue';
    import ArticleEdited from './ArticleEdited.vue';
	import ArticleToPublish from './ArticleToPublish.vue';
	import { UserArticleMixin } from './../../mixins/UserArticleMixin.js';
    import Error from './../errors/Error.vue';
	import Editor from './../../class/Editor.js';

	export default {
		props: ['user'],
		components: {
            ArticleResult,
            ArticleEditor,
            ArticleToEdit,
            ArticleEdited,
            ArticleToPublish,
            Error
        },
		mixins: [ UserArticleMixin ],
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
                paginationResult: {},
                paginationPath: '',
                editor: new Editor(),
                tableType: ''
			}
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
		created() {
			// this.articleList();
		},
		mounted() {
			this.authUser = JSON.parse(this.user);
			this.listenWhenPowerEditorUpdated();
            this.updateArticleData();

            // Bus
            let vm = this;
            ArticleBus.$on('isEditing', data => vm.updateArticle(data));
            ReportingBus.$on('isLoadedListEditedArticles', data => this.listEditedArticles = data);
		},
		methods: {
			articleList() {
				axios.get('/editor/articleList').then(response => {
                    let data = response.data;

                    this.paginationResult = data;
                    this.paginationPath = data.path;
                    this.articles = this.editor.mapResultOfArticles(data.data);
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
                        this.listToEdit[this.index].hr_spent_editor_edit_article = data.times[0];
                        this.listToEdit[this.index].min_spent_editor_edit_article = data.times[1];
                        this.listToEdit[this.index].sec_spent_editor_edit_article = data.times[2];
                        break;
                    case 'article-edited':
                        this.listEditedArticles[this.index].spin = data.article;
                        this.listEditedArticles[this.index].hr_spent_editor_edit_article = data.times[0];
                        this.listEditedArticles[this.index].min_spent_editor_edit_article = data.times[1];
                        this.listEditedArticles[this.index].sec_spent_editor_edit_article = data.times[2];
                        break;
                };


            },

			updateRecord(data) {
				if (data) {
                    this.setupToUpdateRecord(data);
					// this.isEdit = false;  // close the article-editor component
					// this.articles[this.index].spin = data.article;
                    // this.articles[this.index].hr_spent_editor_edit_article = data.times[0];
                    // this.articles[this.index].min_spent_editor_edit_article = data.times[1];
                    // this.articles[this.index].sec_spent_editor_edit_article = data.times[2];

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
                    console.log(data);
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
            }
		}
	}
</script>

<style scoped>
	.Editor { padding: 0 7em; }
	.Copyscape { margin-top: 5em; }
</style>