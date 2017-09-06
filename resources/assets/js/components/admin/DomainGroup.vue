<template>
    <div class="DomainGroup">
        <h2>Domain Group</h2><hr>

        <form method="POST" role="form" @submit.prevent="">
            <div class="form-group">
                <label for="name">Group Name</label>
                <input type="text" class="form-control" v-model="group.name">
            </div>

            <div class="form-group">
                <label for="name">Assign User</label>
                <multiselect v-model="value" :options="options"></multiselect>
            </div>

            <button type="submit" class="btn btn-success">Save Group</button>
        </form>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';

    export default {
        components: { Multiselect },
        data() {
            return {
                users: [],
                group: {
                    name: '',
                    user_id: []
                },
                value: null,
                options: ['list', 'of', 'options']
            };
        },
        watch: {

        },
        created() {
            this.userList();
        },
        mounted() {
        },
        methods: {
            userList() {
                axios.get('/user/userList').then(response => this.users = response.data);
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
