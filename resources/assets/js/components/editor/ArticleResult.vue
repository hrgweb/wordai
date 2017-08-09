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
		        	</td>
		        </tr>
		    </tbody>
		</table>
	</div>
</template>

<script>
	export default {
		props: ['articles'],
		methods: {
			editArticle(article, index) {
				this.$emit('isEditing', {
					article: article,
					index: index
				});
			}
		}
	}
</script>