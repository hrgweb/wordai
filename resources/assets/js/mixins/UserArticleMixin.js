export const UserArticleMixin = {
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
};