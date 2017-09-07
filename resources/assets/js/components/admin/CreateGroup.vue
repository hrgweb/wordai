<template>
    <div class="Group">
        <h2>Create Group</h2>

        <form method="POST" role="form" @submit.prevent>
            <div class="form-group">
                <label for="name">Group Name</label>
                <input type="text" class="form-control" v-model="form.name" @keyup.enter="onSubmit">
            </div>
        </form>

        <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Group Name</th>
                <th>Action</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                form: new Form({ name: '' })
            }
        },
        mounted() {
            console.log('ready');
        },
        methods: {
            onSubmit() {
                this.form.post('/admin/addGroup')
                    .then(data => console.log(data));
            }
        }
    }
</script>