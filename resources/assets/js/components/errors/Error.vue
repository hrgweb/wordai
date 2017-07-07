<template>
	<ul class="alert alert-danger">
		<li v-if="isInputValidation" v-for="error in list">{{ error[0] }}</li>
		<li v-else>{{ list }}</li>
	</ul>
</template>

<script>
	export default {
		props: ['list', 'type'],
		data() {
			return {
				isInputValidation: true
			}
		},
		computed: {
			errorType() {
				this.isInputValidation = (this.type === 1) ? true: false;
			}
		}
	}
</script>

<style scoped>
	ul { padding-left: 2em; }
</style>