<template>
	<div class="Domain">
		<h2>Domain</h2>

		<!-- Domain edit -->
		<domain-edit
			v-if="isEdit"
			@closeDomainEdit="hideDomainEdit"
			:token="token"
			:raw="raw">
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

			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Domain Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(url, index) in domains">
						<td>{{ ++index }}</td>
						<td>{{ url.domain }}</td>
						<td>
							<a href="#" class="btn btn-info" @click="displayDomainEdit(url, index)">Edit</a>
							<a href="#" class="btn btn-danger" v-if="isAdmin" @click="removeDomain(url, index)">Remove</a>
						</td>
					</tr>

					<!-- <tr v-for="url in domains"><pre>{{ url.domain }}</pre></tr> -->
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import DomainEdit from './DomainEdit.vue';

	export default {
		props: ['token', 'user'],
		components: { DomainEdit },
		mixins: [ CrudMixin ],
		data() {
			return {
				domain: '',
				domainIndex: 0,
				domains: [],
				raw: {},
				isAdmin: false,
			}
		},
		watch: {
			authUser(data) {
				this.isAdmin = data.user_level_id === 2 ? true : false;
			}
		},
		created() {
			this.domainList();
		},
		mounted() {
			this.authUser = JSON.parse(this.user);
		},
		methods: {
			domainList() {
				axios.get('/admin/domainList').then(response => this.domains = response.data);
			},
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

						this.domains.push(data);
						this.domain = '';
					}
				});
			},
			displayDomainEdit(url, index) {
				this.domainIndex = --index;
				this.isEdit = true;
				this.raw = url;
			},
			hideDomainEdit(payload) {
				this.isEdit = false;

				// check if payload is not empty
				if (payload) this.domains[this.domainIndex].domain = payload.domain;
			},
			removeDomain(url, index) {
				const data = {
					id: url.id
				};

				axios.delete('/admin/removeDomain', { params: data }).then(response => {
					let data = response.data;

					if (data) {
						this.domains.splice(--index, 1);

                        new Noty({
                            type: 'error',
                            text: `<b>${url.domain}</b> successfully removed.`,
                            layout: 'bottomLeft',
                            timeout: 5000
                        }).show();
					}
				})
			}
		}
	}
</script>