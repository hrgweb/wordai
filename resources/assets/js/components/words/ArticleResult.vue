<template>
	<div class="ArticleResult">
		<div class="Input">
			<!-- Search for -->
			<label for="searchType">Search for</label>&nbsp;
			<select id="searchType" v-model="type">
				<option value="doc_title">Title</option>
				<option value="keyword">Keyword</option>
			</select>

			<!-- Search input -->
			<input type="text" placeholder="Type text here" v-model="search" @keyup="goSearch">
			&nbsp;&nbsp;

			<!-- Sort by -->
			<label for="sortBy">Sort by</label>
			<select id="sortBy" v-model="sort" @change="orderArticles">
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
			            <!-- <th class="text-center">Actions</th> -->
			        </tr>
			    </thead>
			    <tbody>
					<tr v-for="article in filterArticles">
						<td>{{ article.created_at }}</td>
						<td>{{ article.doc_title }}</td>
						<td>{{ article.keyword }}</td>
						<td>{{ article.domain }}</td>
						<!-- <td></td> -->
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
				articleList: [],
				search: '',
				type: 'doc_title',
				sort: 'a-z',
				sortBy: ['A-Z', 'Z-A']
			}
		},
		computed: {
			filterArticles() {
				return this.articleList.filter((article) => {
					return article[this.type].match(new RegExp(this.search, 'i'));
				});
			},
		},
		mounted() {
			this.articleList = this.articles;
		},
		methods: {
			orderArticles() {
				if (this.search.length > 0) {
					this.articleList = this.filterArticles.sort((a, b) => {
						return (this.sort === 'a-z') ? a[this.type] > b[this.type] : a[this.type] < b[this.type];
					});
				} else {
					this.articleList = this.articleList.sort((a, b) => {
						return (this.sort === 'a-z') ? a[this.type] > b[this.type] : a[this.type] < b[this.type];
					});
				}
			},

			goSearch() {
				if (this.search.length > 0) {
					return this.filterArticles;
				} else {
					this.articleList = this.articles;
				}
			}
		}
	}
</script>

<style scoped>
	input, select {
	    height: 2em;
	    padding: 0 0.5em 0 1em;
	    border: 1px solid silver;
	}
</style>