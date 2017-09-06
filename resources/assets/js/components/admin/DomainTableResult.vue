<template>
    <tr>
        <td>{{ orderNum }}</td>
        <td>{{ url.domain }}</td>
        <td>
            <a href="#" class="btn btn-info" @click="domainBus.displayDomainEdit(url, index)">Edit</a>
            <a
                href="#"
                class="btn btn-danger"
                :disabled="isRemoveDisable"
                v-if="domainBus.isAdmin"
                @click="removeDomain(url, index)"
            >
                Remove
            </a>
        </td>
        <td>
            <div class="delete" v-if="aboutToRemove">

                <form method="POST" class="form-inline" @submit.prevent>
                    <!-- <input type="hidden" name="_token" :value="token"> -->

                    <span>Remove <b>{{ url.domain }}</b> domain?</span> &nbsp;
                    <input
                        type="text"
                        class="remove"
                        placeholder="Enter domain name to remove"
                        v-model="domainToRemove"
                        @keyup.enter="deleteNowDomain(url, orderNum)">
                    <button type="button" class="btn btn-danger" @click="dismissRemove">Cancel</button>
                </form>

            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['domain', 'index'],
        data() {
            return {
                url: {},
                domainBus: DomainBus,
                orderNum: this.index,
                domainToRemove: '',
                aboutToRemove: false,
                isRemoveDisable: false
            }
        },
        mounted() {
            this.url = this.domain;
            this.orderNum++;
        },
        methods: {
            btnStateWhenRemoveDomain(state) {
                this.isRemoveDisable = state;
                this.aboutToRemove = state;
            },

            removeDomain(url, index) {
                this.btnStateWhenRemoveDomain(true);
            },

            deleteNowDomain(url, index) {
                if (this.aboutToRemove === true && url.domain === this.domainToRemove) {
                    const data = { id: url.id };

                    axios.delete('/admin/removeDomain', { params: data }).then(response => {
                        let data = response.data;

                        if (data) {
                            this.domainBus.domains.splice(--index, 1);

                            new Noty({
                                type: 'error',
                                text: `<b>${url.domain}</b> successfully removed.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();
                        }
                    });
                } else {
                    new Noty({
                        type: 'info',
                        text: `Domain name not matched.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
                }
            },

            dismissRemove() {
                this.btnStateWhenRemoveDomain(false);
            }
        }
    }
</script>

<style scoped>
    input.remove { width: 200px; }
</style>