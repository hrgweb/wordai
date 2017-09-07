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
                <th>Group Name</th>
                <th>Action</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <group-table-result
                v-for="(group, index) in groups"
                :group="group"
                :index="index"
                :key="group.id"
                @hasRemove="updateGroupsData">
            </group-table-result>
        </tbody>
    </table>
    </div>
</template>

<script>
    import GroupTableResult from './GroupTableResult.vue';

    export default {
        components: { GroupTableResult },
        data() {
            return {
                name: '',
                form: new Form({ name: '' }),
                groups: [],
                group: {},
                isEdit: false,
                index: 0,
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
            },

            updateGroupsData(data) {
                this.groups.splice(data.index, 1);
            }
        }
    }
</script>