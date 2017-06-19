<template>
	<div class="Article">
		<h1>Generate Article</h1>

		<form method="POST" @submit.prevent="generateArticle">
			<input type="hidden" name="_token" :value="token">

			<div class="form-group">
				<select class="form-control" v-model="pickArticle" @change="displayArticle">
					<option value="select">SELECT ARTICLE</option>
					<option v-for="article in articles" :value="article.id">{{ article.doc_title.toUpperCase() }}</option>
				</select>
			</div>

			<textarea class="form-control" rows="12" :value="raw.spintax"></textarea><br>

			<div class="Result" v-if="isSpin">
				<label for="result">Article</label>
				<p>{{ newArticle }}</p><br>
			</div>
		
			<button type="submit" class="btn btn-primary">Generate</button>
		</form>
	</div>
</template>

<script>
	export default {
		props: ['token'],
		data() {
			return {
				articles: [],
				pickArticle: 'select',
				raw: '',
				newArticle: '',
				isSpin: false
			}
		},
		created() {
			this.getRawArticles();
		},
		methods: {
			getRawArticles() {
				axios.get('/words/rawArticles').then(response => {
					this.articles = response.data;
				});
			},
			displayArticle() {
				this.raw = _.head(this.articles.filter(article => article.id === this.pickArticle));
			},
			generateArticle() {
				axios.post('/words/generateArticle', { spintax: this.raw.spintax }).then(response => {
					this.newArticle = response.data;
					this.isSpin = true;
				});
			}
		}
	}
</script>