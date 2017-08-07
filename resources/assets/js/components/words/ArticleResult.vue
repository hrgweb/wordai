<template>
	<div class="ArticleResult">
		<div class="Input">
			<!-- Search for -->
			<label for="searchType">Search for</label>
			<select id="searchType">
				<option v-for="type in searchType" :value="type.toLowerCase()">{{ type }}</option>
			</select>

			<!-- Search input -->
			<input type="text" v-model="search" placeholder="Type your search here">

			<!-- Sort by -->
			<label for="sortBy">Sort by</label>
			<select id="sortBy">
				<option v-for="sort in sortBy" :value="sort.toLowerCase()">{{ sort }}</option>
			</select>
		</div>

		<div class="Result">
			<table class="table table-striped table-hover">
			    <thead>
			        <tr>
			            <th>Date</th>
			            <th>Title</th>
			            <th>Keyword</th>
			            <th>Domain</th>
			            <th class="text-center">Actions</th>
			        </tr>
			    </thead>
			    <tbody>
					<tr v-for="article in articles">
						<td>{{ article.created_at }}</td>
						<td>{{ article.doc_title }}</td>
						<td>{{ article.keyword }}</td>
						<td>{{ article.domain }}</td>
						<td></td>
					</tr>
			    </tbody>
			</table>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['articles'],
		data() {
			return {
				search: '',
				searchType: ['Title', 'Keyword'],
				sortBy: ['A-Z', 'Z-A']
			}
		},
		mounted() {
			console.log('ready');
		}
	}
</script>