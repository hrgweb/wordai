<template>
    <div class="DomainGroup">
        <h2>Domain Group</h2><hr>

        <form method="POST" role="form">
            <div class="form-group">
                <label for="name">Group Name</label>
                <select class="form-control" v-model="form.group_id" @change="getUsersAssociatedByDomain">
                    <option v-for="group in groups" :value="group.id">{{ group.group }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Assign User</label>
                <multiselect
                    v-model="form.value"
                    :options="users"
                    :multiple="true"
                    :close-on-select="true"
                    :hide-selected="true"
                    label="name"
                    track-by="id"
                >
                    <template slot="tag" scope="props">
                        <span class="multiselect__tag">
                            <span>{{ props.option.name }}</span>
                        </span>
                    </template>
                </multiselect>
            </div>

            <br>
            <button
                type="button"
                class="btn btn-success"
                v-if="! isUpdate"
                ref="btnSave"
                @click.prevent="newGroup"
            >
                Save Group
            </button>
            <button
                type="button"
                class="btn btn-warning"
                v-else
                ref="btnUpdate"
                @click.prevent="updateGroup"
            >
                Update Group
            </button>
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
                groups: [],
                /*group: {
                    group_id: 0,
                    value: []
                },*/
                form: new Form({
                    group_id: 0,
                    value: []
                }),
                groupOfUsers: [],
                isUpdate: false
            };
        },
        created() {
            this.groupList();
            this.userList();
        },
        mounted() {
        },
        methods: {
            groupList() {
                axios.get('/admin/groupList').then(response => this.groups = response.data);
            },

            userList() {
                axios.get('/user/userList').then(response => {
                    this.users = response.data.map(item => {
                        return {
                            id: item.id,
                            name: item.firstname + ' ' + item.lastname
                        }
                    });
                });
            },

            validationFail(group_id) {
                if (
                        group_id === null ||
                        group_id === undefined ||
                        group_id <= 0 ||
                        this.form.value.length <= 0
                    ) {

                    new Noty({
                        type: 'error',
                        text: `Please select group name and assign user.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();

                    return true;

                } else {
                    return false;
                }
            },

            clearForm() {
                this.form['group_id'] = 0;
                this.form['value'] = [];
                this.isUpdate = false;
            },

            newGroup() {
                this.$refs.btnSave.disabled = true;

                // validate
                if (this.validationFail(this.form.group_id)) {
                    this.$refs.btnSave.disabled = false;
                } else {
                    this.form.post('/admin/newGroup')
                        .then(data => {
                            if (data.result) {
                                this.$refs.btnSave.disabled = false;

                                new Noty({
                                    type: 'success',
                                    text: `Successfully saved.`,
                                    layout: 'bottomLeft',
                                    timeout: 5000
                                }).show();

                                // clear group vue data
                                this.clearForm();
                            }
                    });
                }
            },

            usersLoaded(users, isUpdate) {
                this.groupOfUsers = users;
                this.form.value = users;
                this.isUpdate = isUpdate;
            },

            getUsersAssociatedByDomain() {
                if (this.form.group_id > 0) {
                    axios.get('/admin/getUsersAssociatedByDomain?group_id='+this.form.group_id)
                        .then(response => {
                            let data = response.data;

                            // if there is/are users
                            if (data.length > 0) {
                                // map results
                                data = data.map(item => {
                                    return {
                                        id: item.user_id,
                                        name: item.firstname + ' ' + item.lastname
                                    };
                                });

                                this.usersLoaded(data, true);
                            } else {
                                this.usersLoaded([], false);
                            }
                        });
                }
            },

            updateGroup() {
                this.$refs.btnUpdate.disabled = true;

                // validate
                if (this.validationFail(this.form.group_id)) {
                    this.$refs.btnUpdate.disabled = false;
                } else {
                    this.form.patch('/admin/updateGroup')
                        .then(data => {
                            if (data) {
                                this.$refs.btnUpdate.disabled = false;

                                new Noty({
                                    type: 'info',
                                    text: `Successfully updated.`,
                                    layout: 'bottomLeft',
                                    timeout: 5000
                                }).show();

                                // clear group vue data
                                this.clearForm();
                            }
                        });
                }
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
