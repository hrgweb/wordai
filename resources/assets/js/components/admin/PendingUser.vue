<template>
	<div class="User">
		<h2>
			Pending User
			<span class="badge">{{ usersCount }}</span>
		</h2>

		<div class="User__profile list-group" v-for="user in users">
			<a href="#" class="list-group-item">
				<h4 class="list-group-item-heading">{{ user.firstname }} {{ user.lastname }}</h4>
				<p class="list-group-item-text">{{ user.email }}</p>
				<small>{{ user.created_at }}</small>
			</a>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				users: [],
				usersCount: 0
			}
		},
		created() {
			this.pendingUsers();
		},
		methods: {
			pendingUsers() {
				axios.get('/admin/pendingUsers').then(response => {
					this.users = response.data;
					this.usersCount = this.users.length;
				});
			}
		}
	}
</script>

<style scoped>
	.User {
		padding: 0 1em;
	}
</style>