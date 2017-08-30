<template>
    <div class="ReportTable">
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
</template>

<script>
    export default {
        props: ['articles'],
        data() {
            return {
                time: moment
            }
        }
    }
</script>