<template>
    <div class="ReportTable">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Writer</th>
                    <th>Title</th>
                    <th>Article</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(article, index) in articles">
                    <td>{{ ++index }}</td>
                    <td>{{ article.firstname }} {{ article.lastname }}</td>
                    <td>{{ article.doc_title }}</td>
                    <td>
                        <p>{{ (article.article.length > 100) ? article.article.substr(0, 100) + '...' : article.article }}</p>
                    </td>
                    <td>{{ time(article.created_at).format('ll') }}</td>
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
        props: ['articles', 'tableType'],
        data() {
            return {
                time: moment,
                index: 0
            }
        },
        methods: {
            editArticle(article, index) {
                ArticleBus.$emit('isEditing', {
                    article: article,
                    index: index,
                    tableType: this.tableType
                });
            },

            publishBtnState(text, state) {
                this.$refs.btnPublish[this.index].innerText = text;
                this.$refs.btnPublish[this.index].disabled = state;
            },

            publishArticle(article, index) {
                alert('ongoing...')
                /*this.index = index;
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
                });*/
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