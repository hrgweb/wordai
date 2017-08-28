<template>
    <div class="Edited" style="top: 3em;">
        <div class="articles-edited-this-week">
            <h2>
                Articles Edited This Week
                <span class="badge">{{ articlesCount }}</span>
            </h2>
            <p>
                <span>From: <b>{{ fromUtc }}</b></span> -
                <span>To <b>{{ toUtc }}</b></span>
            </p>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Article</th>
                        <th>Keyword</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(article, index) in articles">
                        <td>{{ ++index }}</td>
                        <td>{{ article.doc_title }}</td>
                        <td>{{ (article.article.length > 100) ? article.article.substr(0, 100) + '...' : article.article }}</td>
                        <td>{{ article.keyword }}</td>
                        <td>{{ time(article.created_at).format('LL') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['articles', 'fromUtc', 'toUtc'],
        data() {
            return {
                articlesCount: 0,
                time: moment
            }
        },
        watch: {
            articles() {
                this.articlesCount = this.articles.length;
            }
        }
    }
</script>