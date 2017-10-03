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
			<article-table
                :articles="articles"
                :tableType="tableType">
            </article-table>

            <paginate
                :page-count="pageCount"
                :click-handler="paginatePage"
                :prev-text="'Prev'"
                :next-text="'Next'"
                :container-class="'pagination'">

                <span slot="prevContent">&laquo;</span>
                <span slot="nextContent">&raquo;</span>
            </paginate>
		</div>
	</div>
</template>

<script>
    import Paginate from 'vuejs-paginate';
    import ArticleTable from './ArticleTable.vue';
    import { EditorPaginationMixin } from './../../mixins/EditorPaginationMixin.js';

	export default {
        components: { Paginate, ArticleTable },
        mixins: [ EditorPaginationMixin ],
		data() {
			return {
				articles: [],
				search: '',
				type: 'doc_title',
				sort: 'a-z',
				sortBy: ['A-Z', 'Z-A'],
				isProcess: false,
				index: 0,
                isArticlesLoaded: false,
                tableType: 'writer-article'
			}
		},
        watch: {
            articles(data) {
                this.isArticlesLoaded = data.length > 0 ? true : false;

                return data.filter((article) => {
                    return article[this.type].match(new RegExp(this.search, 'i'));
                });
            }
        },
        created() {
            this.userArticles('/user/userArticles'+this.pagePath);
        },
		methods: {
            userArticles(url) {
                axios.get(url).then(response => {
                    let payload = response.data;

                    this.articles = payload.data;
                    this.pageCount = payload.last_page;
                    this.urlPath = payload.path;
                });
            },

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

			btnStateIfArticleIsProcess(text, color) {
				this.$refs.editArticle[this.index].disabled = true;
				this.$refs.editArticle[this.index].innerHTML = text;
				this.$refs.editArticle[this.index].style.backgroundColor = color;
			},

			editArticle(article, index) {
				this.index = index;

				axios.get('/user/editArticle?wordId='+article.id).then(response => {
					let data = response.data;

                    // check if article is approve
                    if (data.isArticleApprove === 1) {
                        this.btnStateIfArticleIsProcess('Approved', '#6CDA6C');
                    } else {
                        this.$emit('isEdit', {
                            data: data,
                            index: index
                        });
                    }

					// check if spintax is empty & isProcess is 0
					/*if (data.isProcess === 0) {
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
								type: 'error',
								text: `<b>${articleTitle}</b> article already processed. You can't process and article that is done already.`,
								layout: 'bottomLeft',
								timeout: 5000
							}).show();
						}
					}*/
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
</style>