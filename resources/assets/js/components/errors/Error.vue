<template>
	<ul class="alert alert-danger">
		<li v-for="error in list">{{ error[0] }}</li>
	</ul>
</template>

<script>
	export default {
		props: ['list']
	}
</script>

<style scoped>
	ul { padding-left: 2em; }
</style>