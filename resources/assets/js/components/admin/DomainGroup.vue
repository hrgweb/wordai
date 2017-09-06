<template>
    <div class="DomainGroup">
        <h2>Domain Group</h2><hr>

        <form method="POST" role="form" @submit.prevent="newGroup">
            <div class="form-group">
                <label for="name">Group Name</label>
                <select class="form-control" v-model="group.group_id">
                    <option v-for="group in groups" :value="group.id">{{ group.group }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Assign User</label>
                <multiselect
                    v-model="group.value"
                    :options="users"
                    :multiple="true"
                    :close-on-select="true"
                    :hide-selected="true"
                    label="name"
                    track-by="id">
                    <template slot="tag" scope="props">
                        <span class="multiselect__tag">
                            <span>{{ props.option.name }}</span>
                        </span>
                    </template>
                </multiselect>
            </div>

            <br>
            <button type="submit" class="btn btn-success" :disabled="btnGroupDisable">Save Group</button>
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
                group: {
                    group_id: 0,
                    value: []
                },
                btnGroupDisable: false
            };
        },
        watch: {

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

            newGroup() {
                this.btnGroupDisable = true;

                // validate
                let group_id = this.group.group_id;

                if (
                        group_id === null ||
                        group_id === undefined ||
                        group_id <= 0 ||
                        this.group.value.length <= 0
                    ) {
                    this.btnGroupDisable = false;

                    new Noty({
                        type: 'error',
                        text: `Please select group name and assign user.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();

                } else {
                    axios.post('/admin/newGroup', this.group).then(response => {
                        if (response.data) {
                            this.btnGroupDisable = false;

                            new Noty({
                                type: 'success',
                                text: `1 record successfully saved.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();

                            // clear group vue data
                            this.group = {
                                group_id: 0,
                                value: []
                            };
                        }
                    });
                }

            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
