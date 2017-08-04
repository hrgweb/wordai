<template>
	<div class="Editor">
		<!-- Article Detail -->
		<div class="ArticleDetail">
			<h3>Spintax Result</h3>
			<p>{{ article.spintax }}</p>

			<h3>Processed Article</h3>
			<p>{{ article.spin }}</p>
			<div id="editor">{{ article.spin }}</div>
		</div>

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
	export default {
		components: { ArticleResult },
		data() {
			return {
				articles: [],
				article: {},
				index: 0,
				isArticlesNotEmpty: false,
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
			}
		}
	}
</script>

<style scoped>
	.Editor { padding: 0 7em; }
	h3 { text-align: center; }
	p { white-space: pre-wrap; }
</style>