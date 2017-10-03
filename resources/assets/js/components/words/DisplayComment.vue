<template>
    <div class="overlay">
        <div class="Comment">
            <h2>Display Comment</h2>
            <span>
                Article: &nbsp; <em>"{{ article.doc_title }}"</em>
            </span><hr>

            <!-- close button -->
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" @click="$emit('closeCommentPanel')">&times;</button>

            <div class="form-group">
                <span>Reason For Decline</span>
                <p>{{ article.reasonArticleNotAprrove }}</p>
            </div>

            <div class="form-group">
                <span>Comment</span>
                <p>{{ article.reasonArticleNotAprroveBody }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['article']
    }
</script>

<style scoped>
    .Comment p { font-size: 1.2em; }
</style>