<template>
	<div class="Word">
		<h1>Article</h1>

		<div class="Word__result" v-if="isSuccess">
			<pre>{{ result }}</pre>
			<br>
			<h3 class="text-center">Spin Tax</h3>
			<p style="white-space: pre-wrap;">{{ newWords }}</p>
		</div>

		<form method="POST" @submit.prevent="spinTax">
			<div class="form-group">
				<span>Words count: <b>{{ count }}</b></span>
			</div>

			<div class="form-group">
				<label for="docTitle">Document Title</label>
				<input type="text" class="form-control" v-model="docTitle">
			</div>

			<div class="form-group">
				<label for="domName">Domain Name</label>
				<select class="form-control" v-model="domName">
					<option value="http://www.google.com">http://www.google.com</option>
					<option value="http://www.youtube.com">http://www.youtube.com</option>
					<option value="http://www.cnn.com">http://www.cnn.com</option>
				</select>
			</div>
	
			<!-- <textarea class="form-control" rows="8" :maxlength="wordsMax" v-model="words" @keyup="wordCount"></textarea> -->
			<textarea class="form-control" rows="8" v-model="words" @keyup="wordCount"></textarea>

			<label for="">Protected Terms</label>
			<textarea class="form-control" rows="8" v-model="protected" @keyup="wordCount"></textarea>

			<label for="">Synonyms</label>
			<textarea class="form-control" rows="8" v-model="synonyms" @keyup="wordCount"></textarea>

			<br>
			<button type="submit" class="btn btn-primary">Spin Tax</button>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span><br>
		</form>
	</div>
</template>

<script>
	export default {
		props: [ 'user' ],
		data() {
			return {
				wordsMax: 1800,
				count: 0,
				words: '',
				newWords: '',
				result: {},
				error: '',
				isLoading: false,
				isSuccess: false,
				authUser: {},
				protected: '',
				synonyms: '',
				docTitle: '',
				domName: 'http://www.cnn.com'
			}
		},
		created() {
			this.authUser = JSON.parse(this.user);
		},
		mounted() {
			// console.log(axios);
		},
		methods: {
			wordCount() {
				let count = 1;
				let words = this.words.trim();

				for (let i=0; i<words.length; i++) {
					let char = words.charAt(i);

					if (char === ' ') {
						count++;
					}
				}

				this.count = count;
			},
			spinTax() {
				const data = { 
					words: this.words,  
					protected: this.protected,
					synonyms: this.synonyms
				};

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
							const params = {
								user_id: this.authUser.id,
								doc_title: this.docTitle,
								dom_name: this.domName,
								spintax: data.text
							}
							this.saveSpinTax(params);
						}

						// console.log(response.data);
					})
					.catch(error => this.error = error.response.data);
			},
			saveSpinTax(input) {
				axios.post('/words/postSpinTax', input).then(response => console.log(response.data));
			}
		}
	}
</script>

<style scoped>
	.Word {
	    padding: 0 20px;
	}

	.Word__result {
		width: 100%;
		margin-bottom: 3em;
    	overflow-y: hidden;
	}
</style>

