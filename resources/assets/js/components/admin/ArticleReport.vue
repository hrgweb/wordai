<template>
    <div class="Report">
        <!-- New Articles This Week -->
        <div class="articles-this-week">
            <h2>
                Articles This Week
                <span class="badge">{{ newArticlesCount }}</span>
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
                        <td>{{ article.article }}</td>
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
                newArticlesCount: 0,
                time: moment,
            }
        },
        watch: {
            articles(data) {
                this.newArticlesCount = data.length;
            }
        }
    }
</script>