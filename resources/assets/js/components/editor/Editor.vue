<template>
	<div class="Editor">
		<h2>Editor</h2>

		<article-result
			:articles="articles"
			v-if="isArticlesNotEmpty">
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
		methods: {
			articleList() {
				axios.get('/editor/articleList').then(response => this.articles = response.data);
			},

			editArticle(article, index) {
				console.log(article, index);
			}
		}
	}
</script>

<style scoped>
	.Editor { padding: 0 7em; }
</style>