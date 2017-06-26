<template>
	<div class="wrapper">
		<div class="Domain">
			<h2>Assign Domain</h2>

			<!-- close button -->
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true" @click="$emit('isClose')">&times;</button>

			<form method="POST">
				<input type="hidden" name="_token" :value="token">

				<div class="form-group">
					<!-- <select class="form-control">
						<option value="select">Select</option>
						<option v-for="" value=""></option>
					</select> -->
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
</template>

<script>
	export default {
		props: [ 'user', 'token' ],
		data() {
			return {
				
			}
		},
		mounted() {
			console.log('ready');
		}
	}
</script>

<style scoped>
	.wrapper {
		position: absolute;
		background: rgba(0,0,0,.5);
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		z-index: 99999;
	}

	.Domain {
	    background: #fff;
	    position: relative;
		width: 30%;
	    padding: .1em 1em;
	    margin: 8em auto 0;
	}
</style>