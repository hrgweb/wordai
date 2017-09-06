<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Domain Name</th>
                <th>Action</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <domain-table-result
                v-for="(domain, index) in domainBus.domains"
                :domain="domain"
                :index="index"
                :key="domain.id">
            </domain-table-result>
        </tbody>
    </table>
</template>

<script>
    import DomainTableResult from './DomainTableResult.vue';

    export default {
        components: { DomainTableResult },
        data() {
            return {
                domainBus: DomainBus
            }
        }
    }
</script>