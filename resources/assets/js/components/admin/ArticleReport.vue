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
            <div class="content">
                <h3>{{ filterGroupBy }}</h3>
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
                            <td>{{ (article.article.length > 100) ? article.article.substr(0, 100) + '...' : article.article }}</td>
                            <td>{{ article.keyword }}</td>
                            <td>{{ time(article.created_at).format('ll') }}</td>
                            <td>
                                <button class="btn btn-info">Edit Spintax</button>
                                <button class="btn btn-warning">Edit Article</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
                report: ReportingBus
            }
        },
        computed: {
            filterGroupBy() {
                return this.report.reportingFilter.groupBy.toUpperCase();
            }
        },
        watch: {
            articles(data) {
                this.newArticlesCount = data.length;
            }
        }
    }
</script>