<template>
	<div class="alert" :class="alertStyle">
		<strong>{{ data.message }} successfully {{ data.action }}.</strong>
	</div>
</template>

<script>
	export default {
		props: ['data'],
		computed: {
			alertStyle() {
				let type = this.data.type;

				return { 
					'alert-success': type === true, 
					'alert-danger': type === false
				}
			}
		}
	}
</script>