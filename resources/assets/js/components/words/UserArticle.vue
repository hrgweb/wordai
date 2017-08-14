<template>
	<div class="UserArticle">
		<div class="User__editor" v-if="isEdit">
			<article-editor
				:data="wordObj"
				@cancelEdit="dismissEdit">
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

			updateArticle(data) {
				if (data) {
					this.isEdit = false;
					this.wordObj = data;
					if (this.isEdit === false) Vue.nextTick(() => this.isEdit = true);
				}
			},

			dismissEdit() {
				this.isEdit = false;
			}
		}
	}
</script>