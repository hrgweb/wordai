<template>
    <div class="Dashboard">
        <!-- <div class="admin-search" v-show="! isEdit"> -->
            <!-- Filter Box -->
            <filter-box></filter-box>

            <!-- Article Report -->
            <article-report></article-report>

            <!-- Article Edited -->
            <article-edited></article-edited>

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
    import FilterBox from './FilterBox.vue';
    import ArticleReport from './ArticleReport.vue';
    import ArticleEdited from './ArticleEdited.vue';
    import ArticleToEdit from './ArticleToEdit.vue';
    import ArticleSpun from './ArticleSpun.vue';
    import PendingUser from './PendingUser.vue';
    // import { ArticleEditorMixin } from './../../mixins/ArticleEditorMixin.js';

    export default {
        props: ['user', 'token'],
        components: {
            FilterBox,
            ArticleReport,
            ArticleEdited,
            ArticleToEdit,
            ArticleSpun,
            PendingUser
        },
        // mixins: [ ArticleEditorMixin ],
        data() {
            return {
                report: ReportingBus,
                searchByArticles: []
            };
        },
        mounted() {
            this.report.setDayFromMonToSun();
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