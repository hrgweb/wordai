<template>
	<div class="form-group">
		<form method="POST" @submit.prevent="generateRespintax(index)">
			<input type="hidden" name="_token" :value="token">

			<textarea class="form-control" rows="12">{{ newParagraph }}</textarea>
			<br>

			<copyscape-result
				:copy="copyscape"
				v-if="responseSuccess">
 			</copyscape-result>

			<button type="submit" class="btn btn-success">Respin</button>
			<button type="button" class="btn btn-warning" @click="processToCopyscape">Copyscape</button>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span>
			<span v-if="isError" style="color: red;">{{ error }}</span><br>
		</form>
	</div>
</template>

<script>
	import CopyscapeResult from './CopyscapeResult.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';

	export default {
		props: [ 'token', 'paragraph', 'index', 'spin' ],
		components: { CopyscapeResult },
		mixins: [ CrudMixin ],
		data() {
			return {
				newParagraph: '',
				error: '',
				isError: false,
				copyscape: {},
				responseSuccess: false
			}
		},
		mounted() {
			this.newParagraph = this.paragraph;
		},
		methods: {
			generateRespintax(index) {
				this.isLoading = true;
				this.isError = false;

				this.spin['paragraph'] = this.paragraph;
				axios.post('/words/generateRespintax', this.spin).then(response => {
					let data = response.data;
					let text = data.text;

					if (data.status === 'Success') {
						this.isLoading = false;
						this.newParagraph = text;

						this.$emit('updateparagraph', {
							index: this.index,
							paragraph: text
						});
					}

					// check if api response is fail
					if (data.status === 'Failure') {
						this.error = data.error;
						this.isError = true;
					}
				});
			},
			processToCopyscape() {
				this.isLoading = true;
				this.isError = false;

				this.spin['paragraph'] = this.paragraph;
				axios.post('/words/processToCopyscape', this.spin).then(response => {
					let data = response.data;

					// api result response success
					this.isLoading = false;
					this.copyscape = data;
					this.responseSuccess = true;

					// check if api response is fail
					if (data.error) {
						this.error = data.error;
						this.isError = true;
						this.responseSuccess = false;
					}
				});
			}
		}
	}
</script>