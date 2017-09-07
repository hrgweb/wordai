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
                    <a href="#" class="btn btn-info" @click="onEdit(group, index)">Edit</a>
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
                groups: [],
                group: {},
                isEdit: false,
                index: 0
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

            postGroup() {
                this.form.post('/admin/addGroup')
                    .then(data => {
                        if (data) {
                            // push to groups data
                            this.groups.push({ id: data.id, group: data.group });

                            new Noty({
                                type: 'success',
                                text: `Group <b>${data.group}</b> successfully added.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();

                            // clear inputs
                            this.form.reset();
                        }
                    });
            },

            onSubmit() {
                return (this.isEdit)
                    ? this.updateGroup()
                    : this.postGroup();
            },

            onEdit(data, index) {
                this.isEdit = true;
                this.group = data;
                this.index = --index;
                this.form.name = data.group;
            },

            updateGroup() {
                this.form.patch('/admin/updateCreateGroup?group_id='+this.group.id)
                    .then(data => {
                        if (data) {
                            this.isEdit = false;
                            this.groups[this.index].group = this.form.name;

                            new Noty({
                                type: 'info',
                                text: `Group <b>${this.form.name}</b> successfully updated.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();

                            // clear inputs
                            this.form.reset();
                        }
                    });
            }
        }
    }
</script>