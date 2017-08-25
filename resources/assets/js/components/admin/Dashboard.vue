<template>
    <div class="Dashboard">
        <!-- Pending User -->
        <pending-user :token="token"></pending-user>
    </div>
</template>

<script>
    import PendingUser from './PendingUser.vue';

    export default {
        props: ['token'],
        components: { PendingUser },
        data() {
            return {

            }
        },
        mounted() {
            console.log('ready');
        }
    }
</script>