<template>
	<div class="Editor">
		<!-- Article Editor -->
		<article-editor
			:article="article"
			v-if="isEdit"
			@isUpdated="updateRecord"
			@isDismiss="dismissUpdate">
		</article-editor>

		<div class="Editor__table" v-if="! isEdit">
			<h2>Editor</h2>

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
					let articleTitle = this.article.doc_title;

					// successfully updated
					new Noty({
						type: 'info',
						text: `<b>${articleTitle}</b> article successfully updated.`,
						layout: 'bottomLeft',
						timeout: 5000
					}).show();
				}
			},

			dismissUpdate() {
				this.isEdit = false;
			}
		}
	}
</script>

<style scoped>
	.Editor { padding: 0 7em; }
</style>