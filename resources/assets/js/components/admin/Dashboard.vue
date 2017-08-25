<template>
    <div class="Dashboard">
        <!-- Article Report -->
        <article-report></article-report>

        <!-- Pending User -->
        <pending-user :token="token"></pending-user>
    </div>
</template>

<script>
    import ArticleReport from './ArticleReport.vue';
    import PendingUser from './PendingUser.vue';

    export default {
        props: ['token'],
        components: { ArticleReport, PendingUser },
        data() {
            return {

            }
        },
        mounted() {
            console.log('ready');
        }
    }
</script>

<style scoped>
    .Dashboard { padding: 0 1em; }
</style>