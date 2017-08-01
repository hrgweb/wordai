<template>
	<div class="User">
		<h2>User List</h2>

		<!-- User Domain -->
		<!-- <user-domain 
			:user="user"
			v-show="showDomain"
			:token="token"
			@isClose="showDomain = false">
		 		</user-domain> -->

		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th>Job</th>
					<th>Status</th>
					<th>Date Registered</th>
				</tr>
			</thead>
			<tbody>
				<!-- <tr v-for="user in users" @click="userClick(user)"> -->
				<tr v-for="user in users">
					<td>{{ user.id }}</td>
					<td>{{ user.firstname }} {{ user.lastname }}</td>
					<td>{{ user.email }}</td>
					<td>{{ lev.level(user.user_level_id) }}</td>
					<td>{{ stat.status(user.status_id) }}</td>
					<td>{{ user.created_at }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<script>
	import UserDomain from './UserDomain.vue';
	import UserStatus from './../../class/UserStatus.js';
	import UserLevel from './../../class/UserLevel.js';

	export default {
		props: [ 'token' ],
		components: { UserDomain },
		data() {
			return {
				users: [],
				user: {},
				showDomain: false,
				stat: new UserStatus(),
				lev: new UserLevel()
			}
		},
		created() {
			this.userList();
		},
		methods: {
			userList() {
				axios.get('/user/userList').then(response => this.users = response.data);
			},
			userClick(user) {
				this.user = user;
				this.showDomain = true;
			}
		}
	}
</script>

<style scoped>
	/*table tr { cursor: pointer; }*/
</style>