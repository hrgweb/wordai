<template>
	<ul class="alert alert-danger" v-if="errorType === true">
		<li v-for="error in list">{{ error[0] }}</li>
	</ul>
	<ul class="alert alert-danger" v-else>
		<li>{{ list }}</li>
	</ul>
</template>

<script>
	export default {
		props: ['list', 'type'],
		computed: {
			errorType() {
				return (this.type === 1) ? true: false;
			}
		}
	}
</script>

<style scoped>
	ul { padding-left: 2em; }

	ul.alert.alert-danger {
	    position: absolute;
	    right: 7em;
	}
</style>