<template>
	<div class="User">
		<!-- Change Role -->
		<change-role
			:user="user"
			:levels="levels"
			v-if="isShowRole"
			@closeRoleComponent="updateDone">
 		</change-role>
		
		<h2>User List</h2>

		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th>Role</th>
					<th>Status</th>
					<th>Date Registered</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(user, index) in users">
					<td>{{ user.id }}</td>
					<td>{{ user.firstname }} {{ user.lastname }}</td>
					<td>{{ user.email }}</td>
					<td>{{ lev.level(user.user_level_id) }}</td>
					<td>{{ stat.status(user.status_id) }}</td>
					<td>{{ user.created_at }}</td>
					<td>
						<button type="button" class="btn btn-success" @click="changeRole(user, index)">Change Role</button>
						<button type="button" class="btn btn-danger">Suspend</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<script>
	import ChangeRole from './ChangeRole.vue';
	import UserStatus from './../../class/UserStatus.js';
	import UserLevel from './../../class/UserLevel.js';

	export default {
		props: [ 'token' ],
		components: { ChangeRole },
		data() {
			return {
				users: [],
				user: {},
				showDomain: false,
				isShowRole: false,
				stat: new UserStatus(),
				lev: new UserLevel(),
				levels: [],
				index: 0
			}
		},
		created() {
			this.userList();
			this.userLevelList();
		},
		methods: {
			userList() {
				axios.get('/user/userList').then(response => this.users = response.data);
			},

			changeRole(user, index) {
				this.isShowRole = true;
				this.user = user;
				this.index = index;
			},

			userLevelList() {
				axios.get('/user/userLevelList').then(response => this.levels = response.data);
			},

			updateDone(data) {
				this.isShowRole = false;

				if (data) {
					this.users[this.index].user_level_id = data.level;
				}
			}
		}
	}
</script>

<style scoped>
	/*table tr { cursor: pointer; }*/
	table tbody tr:hover { background: #EAFFEA; }
</style>