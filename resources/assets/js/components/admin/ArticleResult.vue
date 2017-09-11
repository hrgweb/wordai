<template>
	<div class="ArticleResult">
        <!-- Filter Editor -->
        <filter-editor
            @hasChangeGroupBy="groupByChange"
            @hasChangeOrderBy="orderByChange">
        </filter-editor>

		<table class="table table-striped table-hover">
		    <thead>
		        <tr>
		            <th>Writer</th>
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
		        <tr v-for="(article, index) in articles" :key="index">
		        	<td>{{ article.writer }}</td>
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
    import FilterEditor from './FilterEditor.vue';

	export default {
		props: ['articles'],
        components: { FilterEditor },
		data() {
			return {
                index: 0
            };
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
					article: article.spin,
					spintax: article.spintax
				};

				let vm = this;
				axios.post('/editor/publishArticle', payload).then(function(response) {
					let data = response.data;

					// publish button state
					vm.publishBtnState('Publish', false);

					if (data.status === 'success') {
						// notify user successfully uploade to dropbox
						let articleTitle = payload.title;
						new Noty({
							type: 'success',
							text: `<b>${articleTitle}</b> article successfully uploaded to dropbox.`,
							layout: 'bottomLeft',
							timeout: 5000
						}).show();

					}
				});
			},

            groupByChange(data) {
                if (data) {
                    let filter = data.filter;

                    this.articles = this.articles.sort((a, b) => {
                        let nameA = a[filter.orderBy];
                        let nameB = b[filter.orderBy];

                        if (nameA < nameB) return -1;
                        if (nameA > nameB) return 1;

                        return 0; // names must be equal
                    });
                }
            },

            orderByChange(data) {
                if (data) {
                    let filter = data.filter;

                    this.articles = this.articles.sort((a, b) => {
                        let nameA = a[filter.orderBy];
                        let nameB = b[filter.orderBy];

                        if (filter.sortBy === 'asc') {
                            if (nameA < nameB) return -1;
                            if (nameA > nameB) return 1;

                            return 0; // names must be equal

                        } else {
                            if (nameB < nameA) return -1;
                            if (nameB > nameA) return 1;

                            return 0; // names must be equal
                        }
                    });
                }
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