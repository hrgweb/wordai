<template>
	<div class="Users">
		<!-- Change Role -->
		<change-role
			:user="user"
			:levels="levels"
			v-if="isShowRole"
			@closeRoleComponent="updateRoleDone"
			@closeAccessComponent="updateAccessDone">
 		</change-role>

        <!-- remove user -->
        <div class="overlay" v-if="isAboutToDelete">
            <div class="Remove">
                <h2>Remove User</h2>
                <p>Are you sure you want to remove <b>{{ user.firstname }} {{ user.lastname }}</b>?</p>
                <br>

                <div class="buttons">
                    <button type="button" class="btn btn-success" @click.prevent="deleteUser">Remove</button>
                    <button type="button" class="btn btn-danger" @click.prevent="cancelDelete">Cancel</button>
                </div>
            </div>
        </div>

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
					<td style="position: relative; width: 90px;">
						<span style="float: left;">{{ lev.level(user.user_level_id) }}</span>
						<div class="icon-pe" v-if="user.has_peditor_access === 1">
							<img src="/img/superman-icon.png" alt="icon">
						</div>
					</td>
					<td>{{ stat.status(user.status_id) }}</td>
					<td>{{ user.created_at }}</td>
					<td>
						<button type="button" class="btn btn-info" @click="changeRole(user, index)">Permissions</button>
                        <button type="button" style="width: 70px;" class="btn btn-danger" ref="btnSuspend" @click="suspendUser(user, index)">{{ (user.status_id === 3) ? 'Active' : 'Suspend' }}</button>
						<button type="button" style="width: 70px;" class="btn btn-warning" ref="btnDelete" @click="aboutToDelete(user, index)">Remove</button>
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
				index: 0,
                isAboutToDelete: false
			}
		},
		watch: {
			users() {
				Vue.nextTick(() => {
					$('button:contains(Suspend)').css('background', '#D9534F');
					$('button:contains(Active)').css('background', '#5CB85C');
				});
			}
		},
		created() {
			this.allUsers();
			this.userLevelList();
		},
		methods: {
			allUsers() {
				axios.get('/user/allUsers').then(response => this.users = response.data);
			},

			changeRole(user, index) {
				this.isShowRole = true;
				this.user = user;
				this.index = index;
			},

			userLevelList() {
				axios.get('/user/userLevelList').then(response => this.levels = response.data);
			},

			updateRoleDone(data) {
				this.isShowRole = false;

				if (data) {
					this.users[this.index].user_level_id = data.level;
				}
			},

			setUserStatus(url, userId, statusId) {
				axios.patch(url, { user_id: userId }).then(response => {
					if (response.data) {
						this.users[this.index].status_id = statusId;
					}
				});
			},

			suspendUser(user, index) {
				this.index = index;

				if (this.$refs.btnSuspend[this.index].innerHTML === 'Suspend') {
					this.$refs.btnSuspend[this.index].innerHTML = 'Active';
					this.$refs.btnSuspend[this.index].style.background = '#5CB85C';
					this.setUserStatus('/user/suspendUser', user.id, 3);
				} else {
					this.$refs.btnSuspend[this.index].innerHTML = 'Suspend';
					this.$refs.btnSuspend[this.index].style.background = '#D9534F';
					this.setUserStatus('/user/activateUser', user.id, 1);
				}
			},

			updateAccessDone(data) {
				this.isShowRole = false;

				if (data) {
					let hasAccess = (data.peditor === 'no') ? 0 : 1;
					this.users[this.index].has_peditor_access = hasAccess;
				}
			},

            aboutToDelete(user, index) {
                this.user = user;
                this.index = index;
                this.isAboutToDelete = true;
            },

            deleteUser() {
                axios.delete('/user/removeUser?id='+this.user.id).then(response => {
                    if (response.data) {
                        this.isAboutToDelete = false;
                        this.users.splice(this.index, 1); // remove user on table list

                        // popup message
                        new Noty({
                            type: 'info',
                            text: `1 user successfully removed.`,
                            layout: 'bottomLeft',
                            timeout: 5000
                        }).show();
                    }
                });
            },

            cancelDelete() {
                this.isAboutToDelete = false;
            }
		}
	}
</script>

<style scoped>
	/*table tr { cursor: pointer; }*/
	table tbody tr:hover { background: #EAFFEA; }
	table tbody td { background: #fff; }

    /*button:nth-child(2) { background: #d58512 !important; }*/
    /*button:nth-child(2):hover { background: #C27B15 !important; }*/
	button:hover { border: 1px solid transparent; }

	.icon-pe {
		position: absolute;
	    width: 35px;
	    top: 5px;
	    right: 5px;
	}

	.icon-pe img { width: 100%; }

    .Remove {
        background: #fff;
        margin: 13em auto;
        padding: .1em 1em 1em;
        width: 350px;
    }
</style>