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
			            <th>Domain</th>
			            <th>Keyword</th>
			            <th>Status</th>
			        </tr>
			    </thead>
			    <tbody>
					<tr v-for="article in filterArticles">
						<td>{{ dateTime(article.created_at).format('MMMM D, YYYY @ h:mm:ss a') }}</td>
						<td>{{ article.doc_title }}</td>
						<td>{{ article.domain }}</td>
						<td>{{ article.keyword }}</td>
						<td>
							<button type="button" class="btn btn-info" v-if="! article.isProcess" @click="editArticle(article)">Edit</button>
							<button type="button" class="btn btn-warning" disabled v-else>Waiting For Editing</button>
							<!-- <button type="button" class="btn warning" v-else disabled>Edited</button> -->
						</td>
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
				sortBy: ['A-Z', 'Z-A'],
				dateTime: moment,
				isProcess: false,
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
			},

			editArticle(article) {
				axios.get('/user/editArticle?wordId='+article.id).then(response => {
					let data = response.data;

					// check if spintax is empty & isProcess is 0
					if (data.spintax.length <= 0 && data.isProcess === 0) {
						this.isProcess = false;
					} else {
						this.isProcess = true;
					}

					// emit isEdit event
					this.$emit('isEdit', data);
				});
			}
		}
	}
</script>

<style scoped>
	input, select {
	    height: 2em;
	    padding: 0 0.5em;
	    border: 1px solid silver;
	}
	
	button { width: 150px; }
</style>