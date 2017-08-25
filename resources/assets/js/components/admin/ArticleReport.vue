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
            <div class="articles-content" v-for="article in articles">
                <h3>{{ article.doc_title }}</h3>
                <p>{{ article.article }}</p><br>
                <em>Date Created: </em>
                <b>{{ time(article.created_at).format('LL') }}</b>
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
            }
        },
        watch: {
            articles(data) {
                this.newArticlesCount = data.length;
            }
        }
    }
</script>

<style scoped>
    .articles-content {
        background: #fff;
        padding: 0.1em 1em 1em;
        width: 300px;
        max-width: 300px;
        float: left;
        margin-right: 1em;
        margin-top: 1em;
    }

    .articles-content h3,
    .articles-content p {
        overflow: hidden;
    }

    .articles-content h3 {
        font-size: 1.4em;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .articles-content p {
        white-space: pre-wrap;
    }
</style>