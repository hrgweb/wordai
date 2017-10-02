<template>
    <div class="ReportTable">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Writer</th>
                    <th>Title</th>
                    <th>Domain</th>
                    <th>Keyword</th>
                    <!-- <th>Article</th> -->
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(article, index) in articles">
                    <td>{{ ++index }}</td>
                    <td>{{ article.firstname }} {{ article.lastname }}</td>
                    <td>{{ article.doc_title }}</td>
                    <td>{{ article.domain }}</td>
                    <td>{{ article.keyword }}</td>
                    <!-- <td>
                        <p>{{ (article.article.length > 100) ? article.article.substr(0, 100) + '...' : article.article }}</p>
                    </td> -->
                    <td>{{ time(article.created_at).format('ll') }}</td>
                    <td>
                        <button type="button" class="btn btn-info" @click="editArticle(article, index)">Edit Article</button>
                        <button type="button" class="btn btn-danger" ref="btnPublish" @click="publishArticle(article, index)">Publish</button>
                        <!-- <a href="/admin/downloadArticle">Download -->
                        <!-- <a :href="article.srcPath" :download="article.file" > -->
                            <button type="button" class="btn btn-warning" ref="btnDownload" @click="downloadArticle(article)">Download</button>
                        <!-- </a> -->

                        <!-- <pre>{{ article }}</pre> -->

                        <!-- <form method="POST" action="/admin/downloadArticle">
                            <input type="hidden" name="_token" :value="token">

                            <input type="hidden" name="spin" :value="article.spin">
                            <input type="hidden" name="spintax" :value="article.spintax">
                            <input type="hidden" name="filename" :value="article.filename">

                            <button type="submit" class="btn btn-warning">Download</button>
                        </form> -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import downloadjs from 'downloadjs';
    export default {
        props: ['articles', 'tableType', 'token'],
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
                this.index = --index;
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

                        // remove files
                        axios.get('/editor/removeFiles?article='+data.files[0]+'&spintax='+data.files[1])

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
            },

            downloadArticle(article) {
                // axios.post('/admin/downloadArticle', article).then(response => console.log(response.data));

                let filename = article.domain + '-' + article.doc_title + '-' + article.keyword + '.txt';

                downloadjs(article.spin, 'article_'+filename, 'text/plain');
                downloadjs(article.spintax, 'spintax_'+filename, 'text/plain');
            }
        }
    }
</script>