<template>
    <div class="Dashboard">
        <!-- <div class="admin-search" v-show="! isEdit"> -->
            <!-- ArticleToEdit -->
            <article-to-edit></article-to-edit>

            <!-- Article Spun -->
            <article-spun></article-spun>

            <!-- Pending User -->
            <pending-user :token="token"></pending-user>
        <!-- </div> -->
    </div>
</template>

<script>
    import ArticleToEdit from './ArticleToEdit.vue';
    import ArticleSpun from './ArticleSpun.vue';
    import PendingUser from './PendingUser.vue';

    export default {
        props: ['user', 'token'],
        components: {
            ArticleToEdit,
            ArticleSpun,
            PendingUser
        },
        data() {
            return {
                report: ReportingBus,
                searchByArticles: []
            };
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