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
			<h2>Editor <span class="badge">{{ articlesCount }}</span></h2>

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
	import Error from './../errors/Error.vue';
	// import { EventBus } from './../../eventbus/EventBus.js';

	export default {
		props: ['user'],
		components: { ArticleResult, ArticleEditor, Error },
		mixins: [ UserArticleMixin ],
		data() {
			return {
				isEdit: false,
				articlesCount: 0,
				error: '',
				authUser: {},
				hasPeditorAccess: false
			}
		},
		watch: {
			articles(data) {
				this.articlesCount = data.length;
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
	.Copyscape { margin-top: 5em; }
</style>