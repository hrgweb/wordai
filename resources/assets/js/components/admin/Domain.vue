<template>
	<div class="Domain">
		<h1>Domain</h1>

		<div class="panel panel-default">
			<div class="panel-body">
				<form method="POST" @submit.prevent>
					<input type="hidden" name="_token" :value="token">

					<div class="form-group">
						<label for="domain">New Domain</label>
						<input type="text" class="form-control" id="domain" v-model="domain" @keyup.enter="postDomain">
					</div>

					<button type="submit" class="btn btn-primary">Save</button>
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
					<tr v-for="url in domains">
						<td>{{ url.id }}</td>
						<td>{{ url.domain }}</td>
						<td>
							<a href="#" class="btn btn-warning">Edit</a>
							<a href="#" class="btn btn-danger">Remove</a>
						</td>
					</tr>

					<!-- <tr v-for="url in domains"><pre>{{ url.domain }}</pre></tr> -->
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['token'],
		data() {
			return {
				domain: '',
				domains: []
			}
		},
		created() {
			this.domainList();
		},
		methods: {
			domainList() {
				axios.get('/admin/domainList').then(response => this.domains = response.data);
			},
			postDomain() {
				axios.post('/admin/postDomain', { domain: this.domain }).then(response => this.domains.push(response.data));
			}
		}
	}
</script>