<template>
	<div class="User">
		<!-- <div class="User__pending" v-if="usersCount"> -->
		<div class="User__pending">
			<h2>
				Pending User
				<span class="badge">{{ usersCount }}</span>
			</h2>

			<div class="User__profile list-group" v-for="(user, index) in users">
				<a href="#" class="list-group-item">
					<h4 class="list-group-item-heading">{{ user.firstname }} {{ user.lastname }}</h4>
					<p class="list-group-item-text">{{ user.email }}</p>
					<small>{{ user.created_at }}</small>

					<form method="POST">
						<input type="hidden" name="_token" :value="token">
						<input type="hidden" name="_method" value="PATCH">

						<div class="User__profile-controls pull-right">
							<button type="submit" class="btn btn-success" @click.stop.prevent="verifySignup(user, index)">Confirm</button>
							<button type="submit" class="btn btn-danger">Dismiss</button>
						</div>
					</form>
				</a>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: [ 'token' ],
		data() {
			return {
				users: [],
				usersCount: 0
			}
		},
		created() {
			this.pendingUsers();
		},
		watch: {
			users() {
				this.usersCount = this.users.length;
			}
		},
		methods: {
			pendingUsers() {
				axios.get('/admin/pendingUsers').then(response => this.users = response.data);
			},
			verifySignup(user, index) {
				axios.patch('/user/verifySignup', user).then(response => {
					let data = response.data;

					if (data) {
						this.users.splice(index, 1);
						this.usersCount = this.users.length;
					}
				});
			}
		}
	}
</script>

<style scoped>
	.User {
		padding: 0 1em;
	}

	.User__profile-controls {
		position: relative;
		top: -3em;
	}
</style>