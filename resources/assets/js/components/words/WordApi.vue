<template>
	<div class="Word">
		<h1>Article</h1>

		<div class="Word__result" v-if="isSuccess">
			<!-- <pre>{{ result }}</pre> -->
			<br>
			<h3 class="text-center">Spin Tax</h3>
			<p style="white-space: pre-wrap;">{{ newWords }}</p>
		</div>

		<form method="POST" @submit.prevent="spinTax">
			<input type="hidden" name="_token" :value="token" v-once>

			<div class="form-group">
				<span>Words count: <b>{{ count }}</b></span>
			</div>

			<div class="form-group">
				<label for="doc_title">Document Title</label>
				<input type="text" class="form-control" v-model="spin.doc_title">
			</div>

			<div class="form-group">
				<label for="keyword">Key Word/Phrase field</label>
				<input type="text" class="form-control" maxlength="255" v-model="spin.keyword">
			</div>

			<!-- LSI Terms -->
			<label for="lsi_terms">LSI Terms</label>
			<textarea class="form-control" rows="8" v-model="spin.lsi_terms"></textarea>
			<br>

			<div class="form-group">
				<label for="dom_name">Domain Name</label>
				<select class="form-control" v-model="spin.dom_name">
					<option value="http://www.google.com">http://www.google.com</option>
					<option value="http://www.youtube.com">http://www.youtube.com</option>
					<option value="http://www.cnn.com">http://www.cnn.com</option>
				</select>
			</div>

			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<label for="domain_protected">Domain Protected Terms</label>
					<textarea class="form-control" rows="8" v-model="spin.domain_protected"></textarea>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<label for="protected">Protected Terms</label>
					<textarea class="form-control" rows="8" v-model="spin.protected"></textarea>
				</div>
			</div><br>

			<!-- <textarea class="form-control" rows="8" :maxlength="wordsMax" v-model="words" @keyup="wordCount"></textarea> -->
			<label for="article">Paste Article Below</label>
			<textarea class="form-control" rows="40" v-once v-model="spin.article" @keyup="wordCount"></textarea>
			<br>

			<label for="synonyms">Synonyms</label>
			<textarea class="form-control" rows="8" v-model="spin.synonyms"></textarea>

			<br>
			<error :list="errors" v-if="isFail"></error>
			<br>

			<button type="submit" class="btn btn-primary">Spin Tax</button>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span><br>
		</form>
	</div>
</template>

<script>
	import Error from './../errors/Error.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';

	export default {
		props: [ 'user', 'token' ],
		components: { Error },
		mixins: [ CrudMixin ],
		data() {
			return {
				wordsMax: 1800,
				count: 0,
				newWords: '',
				result: {},
				isFail: false,
				spin: { 
					doc_title: '',
					dom_name: 'http://www.cnn.com',
					keyword: '',
					lsi_terms: '',
					domain_protected: '',
					article: '',
					protected: '',
					synonyms: ''
				}
			}
		},
		created() {
			this.authUser = JSON.parse(this.user);
		},
		methods: {
			wordCount() {
				let count = 1;
				let words = this.spin.article.trim();

				for (let i=0; i<words.length; i++) {
					let char = words.charAt(i);

					if (char === ' ') {
						count++;
					}
				}

				this.count = count;
			},
			spinTax() {
				// show loading
				this.isLoading = true;

				axios.post('/words', this.spin)
				.then(response => {
					let data = response.data;

					this.isFail = data.isError;

					// check if validation fail
					if (this.isFail === true) {
						this.errors = data.errors;
						this.isFail = true;
						this.isLoading = false;
					} else {
						this.isFail = false;

						// check if api response is success
						if (data.status === 'Success') {
							this.newWords = data.text;
							this.result = data;
							this.isSuccess = true;
							this.isLoading = false;

							// post article
							this.spin['article'] = data.text;
							this.postSpinTax(this.spin);
						}
						// console.log(data);
					}
				});
			},
			postSpinTax(data) {
				axios.post('/words/postSpinTax', data).then(response => console.log(response.data));
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

