<template>
	<div class="Detail">
		<h2>Domain Details</h2><br>

		<!-- Error  -->
		<error 
			:list="errors" 
			:type="0"
			v-if="isError">
 		</error>

		<form method="POST" @submit.prevent="saveDetails">
			<!-- Domain -->
			<label for="lsi_terms">Domain</label>
			<select class="form-control" v-model="detail.domain_id">
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
	import WordAi from './../../class/WordAi.js';
	import Error from './../errors/Error.vue';

	export default {
		mixins: [ CrudMixin ],
		components: { Error },
		data() {
			return {
				domains: [],
				detail: {
					domain_id: 'select',
					protected: '',
					synonym: ''
				},
				wordai: new WordAi(),
				isError: false
			}
		},
		watch: {
			errors(list) {
				this.isError = list.length > 0 ? true : false;
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
				if (this.detail.domain_id !== 'select') {
					this.detail['protected'] = this.wordai.protectedTermsSetup(this.detail.protected);

					axios.post('/admin/saveDetails', this.detail).then(response => {
						this.isError = false;
						
						console.log(response.data)
					});
				} else {
					this.errors = 'Please select a domain name.';
					this.isError = true;
				}
			}
		}
	}
</script>

<style scoped>
	div.col-xs-6 { padding: 0; }
</style>