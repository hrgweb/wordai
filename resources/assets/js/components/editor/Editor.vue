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

		<div class="Editor__table" v-if="! isEdit">
            <!-- Article To Edit -->
            <article-to-edit
                :articles="listToEdit">
            </article-to-edit>

            <!-- Article Edited -->
            <article-edited
                :articles="listEditedArticles">
            </article-edited>

            <h2>Article List <span class="badge">{{ articlesCount }}</span></h2>

			<!-- Article Result -->
			<article-result
				:articles="articles"
				v-if="isArticlesNotEmpty"
				@isEditing="updateArticle">
	 		</article-result>
		</div>
	</div>
</template>

<script>
	import ArticleResult from './ArticleResult.vue';
    import ArticleEditor from './ArticleEditor.vue';
    import ArticleToEdit from './ArticleToEdit.vue';
	import ArticleEdited from './ArticleEdited.vue';
	import { UserArticleMixin } from './../../mixins/UserArticleMixin.js';
	import Error from './../errors/Error.vue';

	export default {
		props: ['user'],
		components: {
            ArticleResult,
            ArticleEditor,
            ArticleToEdit,
            ArticleEdited,
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
                listEditedArticles: []
			}
		},
		watch: {
			articles(data) {
				this.articlesCount = data.length;
                this.articlesToEdit(data);
                this.editedArticles(data);
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
			this.articleList();
		},
		mounted() {
			this.authUser = JSON.parse(this.user);
			this.listenWhenPowerEditorUpdated();
            this.updateArticleData();

            // Bus
            let vm = this;
            ArticleBus.$on('isEditing', data => vm.updateArticle(data));
		},
		methods: {
			articleList() {
				axios.get('/editor/articleList').then(response => {
                    this.articles = response.data.map(item => {
                        return {
                            article: item.article,
                            article_type: item.article_type,
                            created_at: item.created_at,
                            doc_title: item.doc_title,
                            domain: item.domain,
                            domain_protected: item.domain_protected,
                            firstname: item.firstname,
                            hr_spent_editor_edit_article: item.hr_spent_editor_edit_article,
                            id: item.id,
                            isCsCheckHitMax: item.isCsCheckHitMax,
                            isEditorUpdateSC: item.isEditorUpdateSC,
                            isRespinHitMax: item.isRespinHitMax,
                            keyword: item.keyword,
                            lastname: item.lastname,
                            lsi_terms: item.lsi_terms,
                            min_spent_editor_edit_article: item.min_spent_editor_edit_article,
                            protected: item.protected,
                            sec_spent_editor_edit_article: item.sec_spent_editor_edit_article,
                            spin: item.spin,
                            spintax: item.spintax,
                            spintax_copy: item.spintax_copy,
                            synonym: item.synonym,
                            writer: item.firstname + ' ' + item.lastname
                        }
                    });
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
            }
		}
	}
</script>

<style scoped>
	.Editor { padding: 0 7em; }
	.Copyscape { margin-top: 5em; }
</style>