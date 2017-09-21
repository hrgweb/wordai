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
                :paginationPath="paginationPath"
                :tableType="tableType">
            </article-to-edit>

            <!-- Article Edited -->
            <article-edited
                :tableType="tableType">
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
	import { ArticleEditorMixin } from './../../mixins/ArticleEditorMixin.js';
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
        mixins: [ ArticleEditorMixin ],
		data() {
			return {
				articlesCount: 0,
				error: '',
                listToEdit: [],
                listEditedArticles: [],
                listArticleToPublish: [],
                paginationResult: {},
                paginationPath: '',
                editor: new Editor(),
			}
		},
		created() {
			// this.articleList();
		},
        mounted() {
            ReportingBus.$on('isLoadedListToEdit', data => this.listToEdit = data);
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