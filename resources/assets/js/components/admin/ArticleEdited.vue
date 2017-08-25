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