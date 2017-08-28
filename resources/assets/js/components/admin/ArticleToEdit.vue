<template>
    <div class="Waiting" style="top: 5em;">
        <div class="articles-to-be-edit">
            <h2>
                Articles Waiting To Be Edit
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(article, index) in articles">
                        <td>{{ ++index }}</td>
                        <td>{{ article.doc_title }}</td>
                        <td>{{ article.article }}</td>
                        <td>{{ article.keyword }}</td>
                        <td>{{ time(article.created_at).format('LL') }}</td>
                        <td>
                            <button class="btn btn-info">Edit Spintax</button>
                            <button class="btn btn-warning">Edit Article</button>
                        </td>
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