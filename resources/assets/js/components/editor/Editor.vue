<template>
	<div class="Editor">
		<!-- Article Editor -->
		<article-editor
			:article="article"
			v-if="isEdit">
		</article-editor>

		<h2>Editor</h2>

		<!-- Article Result -->
		<article-result
			:articles="articles"
			v-if="isArticlesNotEmpty"
			@isEditing="updateArticle">
 		</article-result>
	</div>
</template>

<script>
	import ArticleResult from './ArticleResult.vue';
	import ArticleEditor from './ArticleEditor.vue';

	export default {
		components: { ArticleResult, ArticleEditor },
		data() {
			return {
				articles: [],
				article: {},
				index: 0,
				isArticlesNotEmpty: false,
				isEdit: false
			}
		},
		watch: {
			articles(data) {
				this.isArticlesNotEmpty = (data.length > 0) ? true : false;
			}
		},
		created() {
			this.articleList();
		},
		mounted() {
			$('div#editor').summernote();
		},
		methods: {
			articleList() {
				axios.get('/editor/articleList').then(response => this.articles = response.data);
			},

			updateArticle(data) {
				this.article = data.article;
				this.index = data.index;
				this.isEdit = true;
			}
		}
	}
</script>

<style scoped>
	.Editor { padding: 0 7em; }
</style>