<template>
	<div class="Domain">
		<h1>Protected</h1>

		<!-- Domain edit -->
		<!-- <domain-edit 
			v-if="isEdit" 
			@closeDomainEdit="hideDomainEdit"
			:token="token"
			:raw="raw">
		</domain-edit> -->

		<div class="panel panel-default">
			<div class="panel-body">
				<form method="POST" @submit.prevent="postTerm">
					<input type="hidden" name="_token" :value="token">

					<!-- Notification -->
					<!-- <notification :data="notify" v-if="isSuccess"></notification> -->

					<div class="form-group">
						<label for="terms">Protected Terms</label>
						<textarea class="form-control" rows="6" v-model="terms"></textarea>
					</div>

					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>

			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Protected Terms</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<!-- <tr v-for="(url, index) in domains">
						<td>{{ url.id }}</td>
						<td>{{ url.domain }}</td>
						<td>
							<a href="#" class="btn btn-warning" @click="displayDomainEdit(url, index)">Edit</a>
							<a href="#" class="btn btn-danger" @click="removeDomain(url, index)">Remove</a>
						</td>
					</tr> -->

					<!-- <tr v-for="url in domains"><pre>{{ url.domain }}</pre></tr> -->
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
	import { CrudMixin } from './../../mixins/CrudMixin.js';

	export default {
		props: ['token'],
		mixins: [ CrudMixin ],
		data() {
			return {
				terms: ''
			}
		},
		mounted() {
			console.log('ready');
		},
		methods: {
			postTerm() {
				// hide notification
				this.isSuccess = false;

				axios.post('/admin/postProtectedTerms', { terms: this.terms }).then(response => {
					let data = response.data;

					// response is 200 and return data
					/*if (data) {
						this.domains.push(data);
						this.isSuccess = true;
						this.domain = '';
						this.notify = {
							type: true,
							message: 'Domain',
							action: 'saved'
						};
					}*/

					console.log(data);
				});
			}
		}
	}
</script>