<template>
	<div class="Detail">
		<h2>Domain Details</h2><br>

		<!-- Error  -->
		<error 
			:list="errors" 
			:type="0"
			v-if="isError">
 		</error>

		<form method="POST">
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

			<button type="button" class="btn btn-primary" v-if="! isEdit" @click.prevent="saveDetails">Save Details</button>
			<div class="buttons" v-if="isEdit">
				<button type="button" class="btn btn-warning" @click="updateDetails">Update Details</button>
				<button type="button" class="btn btn-danger" @click="cancelDetails">Cancel</button>
			</div>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span><br>
		</form><hr>

		<!-- DetailTable -->
		<detail-table
			:details="details"
			v-if="isTableShow"
			@isEdited="setDetail">
		</detail-table>
	</div>
</template>

<script>
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import WordAi from './../../class/WordAi.js';
	import Error from './../errors/Error.vue';
	import DetailTable from './DetailTable.vue';

	export default {
		mixins: [ CrudMixin ],
		components: { Error, DetailTable },
		data() {
			return {
				domains: [],
				detail: {
					domain_id: 'select',
					protected: '',
					synonym: ''
				},
				wordai: new WordAi(),
				isError: false,
				isTableShow: false,
				details: [],
				index: 0
			}
		},
		watch: {
			errors(list) {
				this.isError = list.length > 0 ? true : false;
			}
		},
		created() {
			this.listOfDomains();
			this.domainDetails();
		},
		methods: {
			listOfDomains() {
				axios.get('/admin/domainList').then(response => this.domains = response.data);
			},

			mapResults(data) {
				return data.map((item) => {
					return {
						id: item.id,
						domain_id: item.domain_id,
						domain: item.domain.domain,
						protected: item.protected,
						synonym: item.synonym,
						created_at: item.created_at
					};
				});
			},

			domainDetails() {
				axios.get('/admin/domainDetails').then(response => {
					this.details = this.mapResults(response.data);
					this.isTableShow = this.details.length > 0 ? true : false;
				});
			},

			saveDetails() {
				if (this.detail.domain_id !== 'select') {
					this.detail['protected'] = this.wordai.protectedTermsSetup(this.detail.protected);

					axios.post('/admin/saveDetails', this.detail).then(response => {
						this.isError = false;

						response.data['domain'] = $('select option[value='+this.detail.domain_id+']').text();
						this.details.push(response.data);  	// push to details
						this.clearInputs(); 				// clear inputs
					});
				} else {
					this.errors = 'Please select a domain name.';
					this.isError = true;
				}
			},

			setDetail(data) {
				let item = data.detail;

				this.isEdit = true;
				this.index = data.index;
				this.detail = {
					id: item.id,
					domain_id: item.domain_id,
					protected: item.protected,
					synonym: item.synonym,
					created_at: item.created_at
				};
			},

			updateDetails() {
				Vue.nextTick(() => {
					this.detail['domain'] = $('select option[value='+this.detail.domain_id+']').text();
				});

				axios.patch('/admin/updateDetails', this.detail).then(response => {
					// override details on specific index
					Vue.set(this.details, this.index, this.detail);

					// close buttons and clear inputs
					this.cancelDetails();
				});
			},

			clearInputs() {
				this.detail = {
					domain_id: 'select',
					protected: '',
					synonym: ''
				};
			},

			cancelDetails() {
				this.isEdit = false;
				this.clearInputs();
			}
		}
	}
</script>

<style scoped>
	div.col-xs-6 { padding: 0; }
</style>