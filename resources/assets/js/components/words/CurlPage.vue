<template>
	<div class="Curl">
		<form action="" method="POST" role="form">
			<input type="hidden" name="_token" :value="token">

			<button type="button" class="btn btn-default" @click="runCurl">Run Curl</button>
		</form>
	</div>
</template>

<script>
	export default {
		props: ['token'],
		data() {
			return {
				
			}
		},
		mounted() {
			console.log('ready');
		},
		methods: {
			runCurl() {
				axios.post('/words/runCurl', { url: 'https://cnn.com' })
					.then(response => console.log(data));
			}
		}
	}
</script>