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
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<label for="lsi_terms">Domain</label>
				<select class="form-control" v-model="detail.domain_id">
					<option id="domain" value="select">Select a domain</option>
					<option id="domain" v-for="domain in domainBus.domains" :value="domain.id">{{ domain.domain }}</option>
				</select>
			</div>

			<!-- Portfolio -->
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding-left: 1em;">
				<label for="portfolio">Portfolio</label>
				<select class="form-control" v-model="detail.domain_id">
					<option id="domain" value="select">Select a domain</option>
					<option id="domain" v-for="domain in domains" :value="domain.id">{{ domain.domain }}</option>
				</select>
			</div>

			<!-- Edit - domain name -->
			<div class="form-group">
				<b id="domain" style="font-size: 1.5em;">{{ detail.domain }}</b>
			</div>

			<!-- Users -->
			<div class="form-user" v-if="hasUser">
				<label for="user" style="margin-top: 1em;">Assign Users</label>
                <multiselect
                    v-model="detail.user"
                    :options="users"
                    :multiple="true"
                    :close-on-select="true"
                    :hide-selected="true"
                    label="name"
                    track-by="id"
                >
                    <template slot="tag" scope="props">
                        <span class="multiselect__tag">
                            <span>{{ props.option.name }}</span>
                            <span class="multiselect__tag-icon" @click="props.remove(props.option)"></span>
                        </span>
                    </template>
                </multiselect>
			</div><br>

			<!-- LSI Terms -->
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding-right: 1em;">
				<label for="protected">Protected Terms</label>
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
			v-if="isResultNotEmpty"
			@isEdited="setDetail"
			@isRemoving="removeDetails">
		</detail-table>
	</div>
</template>

<script>
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import WordAi from './../../class/WordAi.js';
	import Error from './../errors/Error.vue';
	import DetailTable from './DetailTable.vue';
	import User from './../../class/User.js';
    import Multiselect from 'vue-multiselect';

	export default {
        props: ['user'],
		components: { Error, DetailTable, Multiselect },
        mixins: [ CrudMixin ],
		data() {
			return {
				domains: [],
				detail: {
					domain_id: 'select',
					protected: '',
					synonym: '',
					user: ''
				},
				wordai: new WordAi(),
				isError: false,
				isResultNotEmpty: false,
				details: [],
				index: 0,
				users: [],
				hasUser: false,
				userObj: new User(),
                domainBus: DomainBus
			}
		},
		watch: {
			errors(list) {
				this.isError = list.length > 0 ? true : false;
			},

			details(data) {
				this.isResultNotEmpty = this.details.length > 0 ? true : false;
			},

			users(data) {
				this.hasUser = data.length > 0 ? true : false;
			}
		},
		created() {
			// this.listOfDomains();
			this.domainDetails();
			this.userList();
		},
        mounted() {
            DomainBus.user = JSON.parse(this.user);
        },
		methods: {
			listOfDomains() {
                // axios.get('/admin/domainListNotSet').then(response => this.domains = response.data);
			},

			mapResults(data) {
				return data.map((item) => {
					return {
						id: item.id,
						domain_id: item.domain_id,
						domain: item.domain.domain,
						protected: item.protected.length < 100 ? item.protected : item.protected.substr(0, 100) + '...',
						synonym: item.synonym,
						created_at: item.created_at
					};
				});
			},

			domainDetails() {
				axios.get('/admin/domainDetails').then(response => {
					this.details = this.mapResults(response.data);
				});
			},

			removeDomain() {
				let options = document.getElementsByTagName('select')[0].options;
				this.index = parseInt(this.wordai.domainSelectedIndex(options), 10) - 1;

				this.domains.splice(this.index, 1);
			},

			extractProtectedTerms() {
				return [
					this.wordai.protectedTermsToUppercaseAndLowercase(this.detail.protected, 'toUpperCase'),
					this.wordai.protectedTermsToUppercaseAndLowercase(this.detail.protected, 'toLowerCase'),
					this.wordai.protectedTermsToSentenceCase(this.detail.protected),
					this.wordai.protectedTermsToTitleCase(this.detail.protected)
				]
			},

			saveDetails() {
				if (this.detail.domain_id !== 'select' && this.detail.user.length > 0) {
					this.detail['protected'] = (this.detail.protected.length > 0) ? this.wordai.protectedTermsSetup(this.detail.protected) : '';
					// this.detail['protected'] = 'the man,who cant,be moved,a test,sample';

					let vm = this;
					let options = $('datalist option');

					const data = {
						detail: this.detail,
						user_id: this.userObj.getUserId(vm, options, vm.detail.user).attributes[1].value,
						protectedTerms: (this.detail.protected.length > 0) ? this.extractProtectedTerms().join('|'): ''
					};

					axios.post('/admin/saveDetails', data).then(response => {
						this.isError = false;

						response.data['domain'] = $('select option[value='+this.detail.domain_id+']').text();
						this.details.push(response.data);  	// push to details
						this.clearInputs(); 				// clear inputs
						this.removeDomain();				// remove selected domain
					});
				} else {
					this.errors = 'Please select a domain name and user.';
					this.isError = true;
				}
			},

			setDetail(data) {
				let item = data.detail;

				$('select').hide();	// hide select

				this.isEdit = true;
				this.index = data.index;
				this.detail = {
					id: item.id,
					domain_id: item.domain_id,
					domain: data.e.currentTarget.offsetParent.parentNode.cells[0].innerText,
					protected: item.protected,
					synonym: item.synonym,
					created_at: item.created_at
				};
			},

			updateDetails() {
				Vue.nextTick(() => {
					// this.detail['domain'] = $('select option[value='+this.detail.domain_id+']').text();
					this.detail['domain'] = $('b#domain').text();
				});

				// clean protected terms
				this.detail['protected'] = this.wordai.protectedTermsSetup(this.detail.protected);

				const data = {
					detail: this.detail,
					protectedTerms: this.extractProtectedTerms().join('|')
				};

				axios.patch('/admin/updateDetails', data).then(response => {
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
				$('select').show();	// hide select

				this.isEdit = false;
				this.clearInputs();
			},

			removeDetails(data) {
				this.index = data.index;

				const output = {
					params: {
						id: data.detail.id,
						domain_id: data.detail.domain_id
					}
				};

				axios.delete('/admin/removeDetails', output).then(response => {
					// remove item object in details on specific index
					this.details.splice(this.index, 1);
				});
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
			}
		}
	}
</script>

<style scoped>
	div.col-xs-6 { padding: 0; }
</style>