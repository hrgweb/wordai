<template>
	<div class="Editor">
		<!-- Article Editor -->
		<article-editor
			:article="article"
			v-if="isEdit"
			@isUpdated="updateRecord">
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
	import { UserArticleMixin } from './../../mixins/UserArticleMixin.js';
	// import { EventBus } from './../../eventbus/EventBus.js';

	export default {
		components: { ArticleResult, ArticleEditor },
		mixins: [ UserArticleMixin ],
		data() {
			return {
				isEdit: false
			}
		},
		created() {
			this.articleList();
		},
		methods: {
			articleList() {
				axios.get('/editor/articleList').then(response => this.articles = response.data);
			},

			updateArticle(data) {
				this.isEdit = false;
				this.article = data.article;
				this.index = data.index;
				Vue.nextTick(() => this.isEdit = true );
			},

			updateRecord(data) {
				if (data) {
					this.isEdit = false;
					this.articles[this.index].spin = data.article;
				}
			}
		}
	}
</script>

<style scoped>
	.Editor { padding: 0 7em; }
</style>