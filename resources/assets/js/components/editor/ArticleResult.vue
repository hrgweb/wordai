<template>
	<div class="ArticleResult">
		<table class="table table-striped table-hover">
		    <thead>
		        <tr>
		            <th>User</th>
		            <th>Article Type</th>
		            <th>Domain</th>
		            <th>Title</th>
		            <th>Keyword</th>
		            <th>LSI Terms</th>
		            <th>Domain Protected</th>
		            <th>Protected</th>
		            <th>Synonym</th>
		            <th class="text-center">Actions</th>
		        </tr>
		    </thead>
		    <tbody>
		        <tr v-for="(article, index) in articles">
		        	<td>{{ article.firstname }} {{ article.lastname }}</td>
		        	<td>{{ article.article_type }}</td>
		        	<td>{{ article.domain }}</td>
		        	<td>{{ article.doc_title }}</td>
		        	<td>{{ article.keyword }}</td>
		        	<td>{{ article.lsi_terms }}</td>
		        	<td>{{ article.domain_protected }}</td>
		        	<td>{{ article.protected }}</td>
		        	<td>{{ article.synonym }}</td>
		        	<td>
		        		<button type="button" class="btn btn-info" @click="editArticle(article, index)">Edit Article</button>
		        		<button type="button" class="btn btn-danger" ref="btnPublish" @click="publishArticle(article, index)">Publish</button>
		        	</td>
		        </tr>
		    </tbody>
		</table>
	</div>
</template>

<script>
	export default {
		props: ['articles'],
		data() {
			return { index: 0 };
		},
		methods: {
			editArticle(article, index) {
				this.$emit('isEditing', {
					article: article,
					index: index
				});
			},

			publishBtnState(text, state) {
				this.$refs.btnPublish[this.index].innerText = text;
				this.$refs.btnPublish[this.index].disabled = state;
			},

			publishArticle(article, index) {
				this.index = index;
				this.publishBtnState('Publishing...', true);

				const payload = {
					domain: article.domain,
					title: article.doc_title,
					keyword: article.keyword,
					article: article.spin
				};

				let vm = this;
				axios.post('/editor/publishArticle', payload).then(function(response) {
					let data = response.data;

					if (data.status === 'success') {
						vm.publishBtnState('Publish', false);
					}
				});
			}
		}
	}
</script>

<style scoped>
	table tbody tr:first-child > td:last-child {
	    width: 200px;
	    max-width: 200px;
	}

	button { width: 90px; }
</style>