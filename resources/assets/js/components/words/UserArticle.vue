<template>
	<div class="UserArticle">
		<h2>{{ fullName }}'s Articles</h2>

		<!-- Article Result -->
		<article-result
			:articles="articles"
			v-if="isArticlesNotEmpty">
		</article-result>
	</div>
</template>

<script>
	import ArticleResult from './ArticleResult.vue';
	import { UserArticleMixin } from './../../mixins/UserArticleMixin.js';

	export default {
		props: ['user'],
		components: { ArticleResult },
		mixins: [ UserArticleMixin ],
		data() {
			return {
				authUser: {}
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
			}
		}
	}
</script>