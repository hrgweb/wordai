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
					<tr v-for="(article, index) in filterArticles">
						<td>{{ dateTime(article.created_at).format('MMMM D, YYYY @ h:mm:ss a') }}</td>
						<td>{{ article.doc_title }}</td>
						<td>{{ article.domain }}</td>
						<td>{{ article.keyword }}</td>
						<td>
							<button type="button" class="btn btn-info" ref="editArticle" v-if="! article.isProcess" @click="editArticle(article, index)">Edit</button>
							<button type="button" class="btn" disabled v-else-if="article.isEditorEdit === 1 && article.isProcess === 1">Edited</button>
							<button type="button" class="btn btn-warning" ref="editArticle" disabled v-else-if="article.isProcess === 1">Waiting For Editing</button>
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
				index: 0
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

			btnStateIfArticleIsProcess() {
				this.$refs.editArticle[this.index].disabled = true;
				this.$refs.editArticle[this.index].innerHTML = 'Waiting For Editing';
				this.$refs.editArticle[this.index].style.backgroundColor = '#EC971F';
			},

			editArticle(article, index) {
				this.index = index;

				axios.get('/user/editArticle?wordId='+article.id).then(response => {
					let data = response.data;

					// check if spintax is empty & isProcess is 0
					if (data.spintax.length <= 0 && data.isProcess === 0) {
						this.isProcess = false;
						this.$emit('isEdit', {
							data: data,
							index: index
						}); 	
					} else {
						this.isProcess = true;

						// notify user that article has already process e.g isProcess=1
						if (this.isProcess) {
							// show waiting for edit button
							this.btnStateIfArticleIsProcess();
							
							let articleTitle = data.doc_title;
							new Noty({
								type: 'info',
								text: `<b>${articleTitle}</b> article already processed. You can't process and article that is done already.`,
								layout: 'bottomLeft',
								timeout: 5000
							}).show();
						}
					}
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