<template>
	<div class="Detail">
		<h2>Domain Details</h2><br>

		<form method="POST" @submit.prevent="saveDetails">
			<!-- Domain -->
			<label for="lsi_terms">Domain</label>
			<select class="form-control" v-model="detail.domain">
				<option value="select">Select a domain</option>
				<option v-for="domain in domains" :value="domain.id">{{ domain.domain }}</option>
			</select><br>

			<!-- LSI Terms -->
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding-right: 1em;">
				<label for="lsi_terms">Protected Terms</label>
				<textarea class="form-control" rows="8" v-model="detail.protected"></textarea><br>
			</div>

			<!-- Synonyms -->
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<label for="synonyms">Synonyms</label>
				<textarea class="form-control" rows="8" v-model="detail.synonym"></textarea><br>
			</div>

			<button type="submit" class="btn btn-primary">Save</button>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span><br>
		</form>
	</div>
</template>

<script>
	import { CrudMixin } from './../../mixins/CrudMixin.js';

	export default {
		mixins: [ CrudMixin ],
		data() {
			return {
				domains: [],
				detail: {
					domain: 'select',
					protected: '',
					synonym: ''
				}
			}
		},
		created() {
			this.listOfDomains();
		},
		methods: {
			listOfDomains() {
				axios.get('/admin/domainList').then(response => this.domains = response.data);
			},

			saveDetails() {
				axios.post('/admin/saveDetails', this.detail).then(response => console.log(response.data));
			}
		}
	}
</script>

<style scoped>
	div.col-xs-6 { padding: 0; }
</style>