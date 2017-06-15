<template>
	<div class="Word">
		<h1>WordAI</h1>

		<form method="POST" @submit.prevent="spinTax">
			<div class="form-group">
				<span>Word count: <b>{{ count }}</b></span>
			</div>

			<textarea class="form-control" rows="8" :maxlength="wordsMax" v-model="words" @keyup="wordCount"></textarea>
			<br>
			<button type="submit" class="btn btn-primary">Spin Tax</button>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span><br>
		</form>

		<div class="Word__result" v-if="isSuccess">
			<pre>{{ result }}</pre>
			<br>
			<h3 class="text-center">Spin Tax</h3>
			<p style="wwordCounte-space: pre-wrap;">{{ newWords }}</p>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				wordsMax: 1800,
				count: 1800,
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
			wordCount() {
				this.words = this.words.trim();
				let count = 0;

				for (let i=0; i<this.words.length; i++) {
					let char = this.words.charAt(i);

					if (char === ' ') {
						count++;
					}
				}

				this.count = count;
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

