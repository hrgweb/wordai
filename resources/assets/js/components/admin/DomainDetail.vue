<template>
	<div class="Detail">
		<h2>Domain Details</h2><br>

		<form method="POST">
			<!-- Domain -->
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<label for="lsi_terms">Domain</label>
				<select class="form-control" id="domain" v-model="detail.domain_id">
					<option id="domain" value="select">Select a domain</option>
					<option id="domain" v-for="domain in domainBus.domains" :value="domain.id" :data-name="domain.domain">{{ domain.domain }}</option>
				</select>

                <!-- Edit - domain name -->
                <div class="form-group">
                    <b id="domain" style="font-size: 1.5em;">{{ detail.domain }}</b>
                </div>
			</div>

			<!-- Portfolio -->
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0 0 2em 1em;">
				<label for="portfolio">Portfolio</label>
				<select class="form-control" v-model="detail.domain_id">
					<option id="domain" value="select">Select a domain</option>
					<option id="domain" v-for="domain in domains" :value="domain.id">{{ domain.domain }}</option>
				</select>
			</div><br>

            <div class="wrapper-div" v-if="isDomainChange">
                <!-- Domain Group -->
                <div class="form-group">
                    <label for="group">Domain Group</label>
                    <select class="form-control" v-model="detail.group_id">
                        <option id="group" value="select">Select a group</option>
                        <option id="group" v-for="group in groups" :value="group.id">{{ group.group }}</option>
                    </select>
                </div>

                <!-- Users -->
                <div class="form-user" v-if="hasUser">
                    <label for="user" style="margin-top: 1em;">Assign Users</label>
                    <multiselect
                        v-model="detail.users"
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
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding-right: 1em;">
                    <label for="lsi">LSI Terms</label>
                    <textarea class="form-control" rows="8" v-model="detail.lsi"></textarea><br>
                </div>

                <!-- Protected Terms -->
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding-right: 1em;">
                    <label for="protected">Protected Terms</label>
                    <textarea class="form-control" rows="8" v-model="detail.protected"></textarea><br>
                </div>

                <!-- Synonyms -->
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
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
            </div>
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
	import DetailTable from './DetailTable.vue';
	import User from './../../class/User.js';
    import Multiselect from 'vue-multiselect';

	export default {
        props: ['user'],
		components: { DetailTable, Multiselect },
        mixins: [ CrudMixin ],
		data() {
			return {
				domains: [],
				detail: {
					domain_id: 'select',
                    group_id: 'select',
                    lsi: '',
					protected: '',
					synonym: '',
					users: []
				},
				wordai: new WordAi(),
				isResultNotEmpty: false,
				details: [],
				index: 0,
				users: [],
				hasUser: false,
				userObj: new User(),
                domainBus: DomainBus,
                groups: [],
                isDomainChange: true
			}
		},
		watch: {
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
            this.groupList();
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
						group_id: item.group_id,
						domain: item.domain,
                        protected: item.protected.length < 100 ? item.protected : item.protected.substr(0, 100) + '...',
						// protected_orig: item.protected,
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
				if (this.detail.domain_id !== 'select' && this.detail.users.length > 0) {
					this.detail['protected'] = (this.detail.protected.length > 0) ? this.wordai.protectedTermsSetup(this.detail.protected) : '';
					// this.detail['protected'] = 'the man,who cant,be moved,a test,sample';

					/*let vm = this;
					let options = $('datalist option');

					const data = {
						detail: this.detail,
						user_id: this.userObj.getUserId(vm, options, vm.detail.user).attributes[1].value,
						protectedTerms: (this.detail.protected.length > 0) ? this.extractProtectedTerms().join('|'): ''
					};*/

                    this.detail['domain'] = $("select#domain option:selected").text();
                    // this.detail['protected'] = (this.detail.protected.length > 0) ? this.extractProtectedTerms().join('|'): ''
					axios.post('/admin/saveDetails', this.detail).then(response => {
                        let data = this.mapResults(response.data);

                        if (data) {
                            // for (var i = 0; i < data.length; i++) {
                                this.details.push(data[0]);    // push to details
                            // }

						    // response.data['domain'] = $('select option[value='+this.detail.domain_id+']').text();
                            // this.removeDomain();             // remove selected domain
                            this.clearInputs();                 // clear inputs

                            // notify
                            new Noty({
                                type: 'success',
                                text: `1 record successfully saved.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();
                        }
					});
				} else {
                    new Noty({
                        type: 'error',
                        text: `Please select a domain name and user.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
				}
			},

			setDetail(data) {
				let detail = data.detail.detail;
                let users = this.fullName(data.detail.users);

				$('select#domain').hide();	// hide select

				this.isEdit = true;
				this.index = data.index;
				this.detail = {
					id: detail.id,
					domain_id: detail.domain_id,
                    group_id: detail.group_id > 0 ? detail.group_id : 'select',
					domain: detail.domain,
					protected: detail.protected,
					synonym: detail.synonym,
					created_at: detail.created_at,
                    users: users
				};
			},

			updateDetails() {
				axios.patch('/admin/updateDetails', this.detail).then(response => {
                    // update details data
                    Vue.set(this.details, this.index, response.data);

                    // notify
                    new Noty({
                        type: 'info',
                        text: `1 record successfully updated.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();

					// close buttons and clear inputs
					this.cancelDetails();
				});
			},

			clearInputs() {
				this.detail = {
                    domain_id: 'select',
					group_id: 'select',
					protected: '',
					synonym: '',
                    users: []
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
                        domain_id: data.detail.domain_id,
						group_id: data.detail.group_id
					}
				};

				axios.delete('/admin/removeDetails', output).then(response => {
					// remove item object in details on specific index
					this.details.splice(this.index, 1);

                    // notify
                    new Noty({
                        type: 'error',
                        text: `1 record successfully removed.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
				});
			},

            fullName(data) {
                return data.map(item => {
                    return {
                        id: item.user_id,
                        name: item.firstname + ' ' + item.lastname
                    };
                });
            },

			userList() {
				axios.get('/user/userList').then(response => this.users = this.fullName(response.data));
			},

            groupList() {
                axios.get('/admin/groupList').then(response => this.groups = response.data);
            }
		}
	}
</script>

<style scoped>
	div.col-xs-6 { padding: 0; }
    .Detail select { text-transform: uppercase; }
    .wrapper-div { clear: both; }

    .col-xs-4.col-sm-4.col-md-4.col-lg-4 {
        padding: 0;
    }
</style>