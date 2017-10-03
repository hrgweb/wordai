<template>
    <div class="Dashboard">
        <!-- Pending User -->
        <pending-user :token="token"></pending-user>
    </div>
</template>

<script>
    import PendingUser from './PendingUser.vue';

    export default {
        props: ['user', 'token'],
        components: { PendingUser },
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