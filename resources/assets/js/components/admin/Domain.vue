<template>
	<div class="Domain">
		<h2>Domain</h2>

		<!-- Domain edit -->
		<domain-edit
			v-if="domainBus.isEdit"
			@closeDomainEdit="hideDomainEdit"
			:token="token"
			:raw="domainBus.raw">
		</domain-edit>

		<div class="panel panel-default">
			<div class="panel-body">
				<form method="POST" @submit.prevent="postDomain">
					<input type="hidden" name="_token" :value="token">

					<div class="form-group">
						<label for="domain">New Domain</label>
						<input type="text" class="form-control" id="domain" v-model="domain">
					</div>

					<button type="submit" class="btn btn-success">Save Domain</button>
				</form>
			</div>

			<!-- Domain Table -->
            <domain-table></domain-table>
		</div>
	</div>
</template>

<script>
	import { CrudMixin } from './../../mixins/CrudMixin.js';
    import DomainEdit from './DomainEdit.vue';
	import DomainTable from './DomainTable.vue';

	export default {
		props: ['token', 'user'],
		components: { DomainEdit, DomainTable },
		mixins: [ CrudMixin ],
		data() {
			return {
				domain: '',
				domainIndex: 0,
                domainBus: DomainBus
			}
		},
		mounted() {
			this.authUser = JSON.parse(this.user);

            // Bus
            DomainBus.user = this.authUser;
		},
		methods: {
			postDomain() {
				axios.post('/admin/postDomain', { domain: this.domain }).then(response => {
					let data = response.data;

					// response is 200 and return data
					if (data.isSuccess === false) {
						this.errors = data.result;

                        new Noty({
                            type: 'error',
                            text: `Please enter domain name.`,
                            layout: 'bottomLeft',
                            timeout: 5000
                        }).show();
					} else {
                        let domain = this.domain;
                        new Noty({
                            type: 'success',
                            text: `<b>${domain}</b> successfully saved.`,
                            layout: 'bottomLeft',
                            timeout: 5000
                        }).show();

						DomainBus.domains.push(data);
						this.domain = '';
					}
				});
			},

			hideDomainEdit(payload) {
				DomainBus.isEdit = false;

				// check if payload is not empty
				if (payload) DomainBus.domains[DomainBus.index].domain = payload.domain;
			}
		}
	}
</script>