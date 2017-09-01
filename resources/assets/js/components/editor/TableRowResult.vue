<template>
    <tr>
        <td>{{ index }}</td>
        <td>{{ article.doc_title }}</td>
        <td>{{ (article.article.length > 100) ? article.article.substr(0, 100) + '...' : article.article }}</td>
        <td>{{ article.keyword }}</td>
        <td>{{ time(article.created_at).format('LL') }}</td>
        <td>
            <button class="btn btn-info">Edit Spintax</button>
            <button class="btn btn-warning">Edit Article</button>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['article', 'index'],
        data() {
            return {
                time: moment,
            }
        },
        mounted() {
            console.log(this.article);
        }
    }
</script>