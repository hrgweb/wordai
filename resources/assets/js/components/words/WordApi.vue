<template>
	<div class="Word">
		<h1>WordAI</h1>

		<form method="POST" @submit.prevent="spinTax">
			<div class="form-group">
				<span>Character left: <b>{{ wordsLeft }}</b></span>
			</div>

			<textarea class="form-control" rows="8" :maxlength="wordsMax" v-model="words" @keyup="hit"></textarea>
			<br>
			<button type="submit" class="btn btn-primary">Spin Tax</button>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span>
		</form>

		<div class="Word__result" v-if="isSuccess">
			<h3 class="text-center">Spin Tax</h3>
			<p style="white-space: pre-wrap;">{{ newWords }}</p>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				wordsMax: 1800,
				wordsLeft: 1800,
				words: '',
				newWords: '',
				result: {},
				error: '',
				isLoading: false,
				isSuccess: false
			}
		},
		mounted() {
			// console.log(axios);
		},
		methods: {
			hit() {
				this.wordsLeft = this.wordsMax - this.words.length;
				console.log('hit');
			},
			spinTax() {
				const data = { words: this.words };

				// show loading
				this.isLoading = true;

				axios.post('/words', data)
					.then(response => {
						let data = response.data;
						this.result = data;
						this.newWords = data.text;

						if (data.status === 'Success') {
							this.isSuccess = true;
							this.isLoading = false;

							// post
							this.saveSpinTax({ words: data.text });
						}
					})
					.catch(error => this.error = error.response.data);
			},
			saveSpinTax(input) {
				axios.post('/words/postSpinTax', input).then(response => console.log(response.data));
			}
		}
	}
</script>

<style>
	.Word__result {
		width: 100%;
    	overflow-y: hidden;
	}
</style>

