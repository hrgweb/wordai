<template>
	<ul class="alert alert-danger">
		<li v-if="errorType === true" v-for="error in list">{{ error[0] }}</li>
		<li v-if="errorType === false">{{ list }}</li>
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
</style>