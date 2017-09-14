<template>
    <tr>
        <td>{{ group.group }}</td>
        <td>
            <a href="#" class="btn btn-info" @click="onEdit(group, index)">Edit</a>
            <a
                href="#"
                class="btn btn-danger"
                :disabled="removeDisable"
                v-if="domainBus.isAdmin"
                @click="onDelete(group, index)"
            >
                Remove
            </a>
        </td>
        <td>
            <div class="delete" v-if="aboutToRemove">
                <form method="POST" class="form-inline" @submit.prevent>
                    <!-- <input type="hidden" name="_token" :value="token"> -->

                    <span>Remove <b>{{ group.group }}</b> group?</span> &nbsp;
                    <input
                        type="text"
                        class="remove"
                        placeholder="Enter group name to remove"
                        v-model="groupToRemove"
                        @keyup.enter="removeGroup(group, index)">
                    <button type="button" class="btn btn-danger" @click="dismissRemove">Cancel</button>
                </form>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['group', 'index'],
        data() {
            return {
                aboutToRemove: false,
                removeDisable: false,
                groupToRemove: '',
                form: new Form(),
                domainBus: DomainBus
            }
        },
        methods: {
            removeState(state) {
                this.aboutToRemove = state;
                this.removeDisable = state;
            },

            onDelete(group, index) {
                this.removeState(true);
            },

            removeGroup(data, index) {
                // check if group name is equal to input name
                if (data.group === this.groupToRemove) {
                    // remove group
                    this.form.delete('/admin/removeCreateGroup?group_id='+data.id)
                        .then(response => {
                            if (response) {
                                this.$emit('hasRemove', { index: index });

                                let group = data.group;
                                new Noty({
                                    type: 'error',
                                    text: `<b>${group}</b> successfully removed.`,
                                    layout: 'bottomLeft',
                                    timeout: 5000
                                }).show();
                            }
                        });
                } else {
                    new Noty({
                        type: 'info',
                        text: `Group name not matched.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
                }
            },

            dismissRemove() {
                this.removeState(false);
            },

            onEdit(data, index) {
                this.$emit('onEdit', {
                    isEdit: true,
                    group: data,
                    index: index
                });
            },
        }
    }
</script>