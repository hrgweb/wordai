<template>
    <div class="Dashboard">
        <!-- Articles To Approve -->
        <article-to-approve></article-to-approve>

        <!-- Pending User -->
        <pending-user :token="token"></pending-user>
    </div>
</template>

<script>
    import ArticleToApprove from './ArticleToApprove.vue';
    import PendingUser from './PendingUser.vue';

    export default {
        props: ['user', 'token'],
        components: { ArticleToApprove, PendingUser },
        data() {
            return {
                report: ReportingBus,
                searchByArticles: []
            }
        }
    }
</script>

<style scoped>
    .Dashboard {
        padding: 0 1em;
        margin-bottom: 10em;
    }

    .Filter { margin-top: 0; }
</style>