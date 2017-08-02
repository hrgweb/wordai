<template>
	<div class="overlay">
		<div class="Domain-update">
			<h2>Edit</h2>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true" @click="$emit('closeDomainEdit')">&times;</button>

			<form method="POST" @submit.prevent>
				<input type="hidden" name="_token" :value="token">
				<input type="hidden" name="_method" value="PATCH">

				<div class="form-group">
					<label for="domain">Domain Name</label>
					<input type="text" class="form-control" id="domain" v-model="domain" @keyup.enter="updateDomain">
				</div>

				<!-- Error -->
				<error
					:list="errors"
					:type="1"
					v-if="isError">
				</error>

				<!-- <button type="submit" class="btn btn-primary">Update</button> -->
			</form>
		</div>
	</div>
</template>

<script>
	import Error from './../errors/Error.vue';

	export default {
		props: ['token', 'raw'],
		components: { Error },
		data() {
			return {
				domain: '',
				isError: false,
				errors: []
			}
		},
		mounted() {
			this.domain = this.raw.domain;
		},
		methods: {
			updateDomain() {	
				const data = {
					id: this.raw.id,
					domain: this.domain
				};

				axios.patch('/admin/updateDomain', data).then(response => {
					let data = response.data;

					if (data.isSuccess === false) {
						this.isError = true;
						this.errors = data.result;
					} else {
						const notify = {
							type: true,
							message: 'Domain',
							action: 'updated',
							domain: data.domain
						};

						this.$emit('closeDomainEdit', notify);
						this.isError = false;
					}
				});
			}
		}
	}
</script>

<style scoped lang="scss">
	.overlay {
		position: absolute;
		background: rgba(0,0,0,0.8);
		width: 100%;
		top: 0;
		left: 0;
		bottom: 0;
    	right: 0;
	}

	.Domain-update {
		position: relative;
	    background: #fff;
	    width: 300px;
	    margin: 15em auto;
	    padding: 0.1em 1em 1em;
	}
</style>