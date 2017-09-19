<template>
    <div class="Dashboard">
        <!-- Range Filter -->
        <range-filter></range-filter>

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
    </div>
</template>

<script>
    import FilterBox from './FilterBox.vue';
    import RangeFilter from './RangeFilter.vue';
    import ArticleReport from './ArticleReport.vue';
    import ArticleEdited from './ArticleEdited.vue';
    import ArticleToEdit from './ArticleToEdit.vue';
    import ArticleSpun from './ArticleSpun.vue';
    import PendingUser from './PendingUser.vue';

    export default {
        props: ['token'],
        components: {
            FilterBox,
            RangeFilter,
            ArticleReport,
            ArticleEdited,
            ArticleToEdit,
            ArticleSpun,
            PendingUser
        },
        data() {
            return {
                report: ReportingBus
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