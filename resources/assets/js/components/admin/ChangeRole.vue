<template>
	<div class="overlay">
		<div class="Role">
			<div class="Crole">
				<h2>Change Role</h2><hr>
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true" @click="$emit('closeRoleComponent')">&times;</button>

				<b>{{ user.firstname }} {{ user.lastname }}</b> role: &nbsp;&nbsp;

				<select v-model="data.level" @change="roleNotChange">
					<option :value="data.level">{{ role.level(data.level) }}</option>
					<option v-for="lev in levels" :value="lev.id">{{ lev.user_level }}</option>
				</select><br><br>

				<button type="button" class="btn btn-success btn-block" :disabled="roleNotChange()" @click="updateRole">Update Role</button>
			</div>

			<div class="Peditor">
				<h2>Power Editor Access</h2><hr>

				<b>{{ user.firstname }} {{ user.lastname }}</b> has power editor access? &nbsp;&nbsp;

				<select v-model="data.peditor">
					<option v-for="val in peditorValues" :value="val">{{ val }}</option>
				</select><br><br>

				<button type="button" class="btn btn-success btn-block" :disabled="pEditorNotChange()" @click="updatePeditor">Update Access</button>
			</div>
		</div>
	</div>
</template>

<script>
	import UserLevel from './../../class/UserLevel.js';

	export default {
		props: ['user', 'levels'],
		data() {
			return {
				role: new UserLevel(),
				peditorValues: ['no', 'yes'],
				data: {
					user_id: 0,
					level: 0,
					peditor: 'no'
				}
			}
		},
		computed: {
			hasPeditorAccess() {
				return this.user.has_peditor_access === 0 ? 'no' : 'yes';
			}
		},
		mounted() {
			this.data['user_id'] = this.user.id;
			this.data['level'] = this.user.user_level_id;
			this.data['peditor'] = this.hasPeditorAccess;
		},
		methods: {
			updateRole() {
				axios.patch('/user/updateRole', this.data).then(response => {
					if (response.data) {
						this.$emit('closeRoleComponent', { level: this.data.level });
					}
				});
			},

			roleNotChange() {
				return this.user.user_level_id === this.data.level ? true : false;
			},

			pEditorNotChange() {
				return this.hasPeditorAccess === this.data.peditor ? true : false;
			},

			updatePeditor() {
				axios.patch('/user/updatePeditor', this.data).then(response => {
					if (response.data) {
						this.$emit('closeRoleComponent', { level: this.data.level });
					}
				});
			},
		}
	}
</script>

<style scoped>
	.overlay {
		position: fixed;
	    background: rgba(0,0,0,0.5);
	    top: 0;
	    right: 0;
	    left: 18.3%;
	    bottom: 0;
	    z-index: 10000;
	}

	.Role {
		position: relative;
		background: #fff;
		margin: 12em auto;
		padding: 0.1em 1em 1em;
		width: 300px;
	}

	button.close {
		color: #ff0202;
		top: 0.3em;
		right: 0.4em;
		opacity: 1;
	}
</style>