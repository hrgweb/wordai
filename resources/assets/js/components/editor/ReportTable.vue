<template>
    <div class="ReportTable">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Writer</th>
                    <th>Article Type</th>
                    <th>Domain</th>
                    <th>Title</th>
                    <th>Keyword</th>
                    <th>LSI Terms</th>
                    <!-- <th>Domain Protected</th> -->
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
                    <!-- <td>{{ article.domain_protected }}</td> -->
                    <td>{{ article.protected }}</td>
                    <td>{{ article.synonym }}</td>
                    <td>
                        <button type="button" class="btn btn-info" v-if="! isPublish" @click="editArticle(article, index)">Edit Article</button>
                        <button type="button" class="btn btn-danger" ref="btnPublish" v-else @click="publishArticle(article, index)">Publish</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['articles', 'isPublish', 'tableType'],
        data() {
            return {
                time: moment
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
            }
        }
    }
</script>