<template>
	<div class="UserArticle">
		<div class="User__editor" v-if="isEdit">
			<article-editor
				:data="wordObj"
				@cancelEdit="dismissEdit"
				@isUpdated="updateRecord">
			</article-editor>			
		</div>

		<div class="User__articles" v-else>
			<h2>{{ fullName }}'s Articles</h2><hr>

			<!-- Article Result -->
			<article-result
				:articles="articles"
				@isEdit="updateArticle"
				v-if="isArticlesNotEmpty">
			</article-result>
		</div>
	</div>
</template>

<script>
	import ArticleResult from './ArticleResult.vue';
	import ArticleEditor from './ArticleEditor.vue';
	import { UserArticleMixin } from './../../mixins/UserArticleMixin.js';

	export default {
		props: ['user'],
		components: { ArticleResult, ArticleEditor },
		mixins: [ UserArticleMixin ],
		data() {
			return {
				authUser: {},
				wordObj: {},
				isEdit: false,
				index: 0
			}
		},
		computed: {
			fullName() {
				let user = this.authUser;

				return user.firstname + ' ' + user.lastname;
			}
		},
		created() {
			this.userArticles();
		},
		mounted() {
			this.authUser = JSON.parse(this.user);
		},
		methods: {
			userArticles() {
				axios.get('/user/userArticles').then(response => this.articles = response.data);
			},

			updateArticle(payload) {
				if (payload) {
					this.isEdit = false;
					this.index = payload.index;
					this.wordObj = payload.data;
					if (this.isEdit === false) Vue.nextTick(() => this.isEdit = true);
				}
			},

			dismissEdit() {
				this.isEdit = false;
			},

			updateRecord(data) {
				if (data) {
					this.isEdit = false;
					this.articles[this.index].spin = data.article;
					let articleTitle = this.wordObj.doc_title;

					// successfully updated
					new Noty({
						type: 'info',
						text: `<b>${articleTitle}</b> article successfully updated.`,
						layout: 'bottomLeft',
						timeout: 5000
					}).show();
				}
			}
		}
	}
</script>