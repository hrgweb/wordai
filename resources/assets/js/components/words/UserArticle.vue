<template>
	<div class="UserArticle">
		<div class="User__editor" v-if="isEdit">
			<article-editor
				:data="wordObj"
                :index="index"
				@cancelEdit="dismissEdit">
				<!-- @isUpdated="updateRecord"> -->
			</article-editor>
		</div>

		<div class="User__articles" v-show="! isEdit">
			<h2>{{ fullName }}'s Articles</h2><hr>

			<!-- Article Result -->
			<article-result></article-result>
		</div>
	</div>
</template>

<script>
	import ArticleResult from './ArticleResult.vue';
	import ArticleEditor from './ArticleEditor.vue';

	export default {
		props: ['user'],
		components: { ArticleResult, ArticleEditor },
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
		mounted() {
			this.authUser = JSON.parse(this.user);

            // event bus
            ArticleBus.$on('isEdit', this.updateArticle);
		},
		methods: {
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
			}
		}
	}
</script>