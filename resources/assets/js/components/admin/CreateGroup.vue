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
            <tr v-for="(group, index) in groups">
                <td>{{ ++index }}</td>
                <td>{{ group.group }}</td>
                <td>
                    <a href="#" class="btn btn-info">Edit</a>
                    <a href="#" class="btn btn-danger">Remove</a>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                form: new Form({ name: '' }),
                groups: []
            }
        },
        created() {
            this.groupList();
        },
        methods: {
            groupList() {
                axios.get('/admin/groupList')
                    .then(response => this.groups = response.data);
            },

            onSubmit() {
                this.form.post('/admin/addGroup')
                    .then(data => {
                        if (data) {
                            // push to groups data
                            this.groups.push({ id: data.id, group: data.group });

                            // clear inputs
                            this.form.reset();
                        }
                    });
            }
        }
    }
</script>