<template>
	<div class="Word">
		<h1>Article</h1><hr>

		<div class="Word__result" v-show="isSuccess">
			<!-- <pre>{{ result }}</pre> -->
			<br>
			<h3 class="text-center">Spin Tax</h3>
			<!-- <p style="white-space: pre-wrap;">{{ paragraphs }}</p> -->

			<!-- Seperate paragraph -->
			<separate-paragraph
				:token="token"
				:spin="spin"
				v-for="(para, index) in paragraphs" :paragraph="para" :index="index" key
				@updateparagraph="respinParagraph">
 			</separate-paragraph>
		</div>

		<form method="POST">
			<input type="hidden" name="_token" :value="token">

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
			<error :list="errors" v-if="isValidationFail"></error>
			<br>

			<button type="submit" class="btn btn-primary" @click.prevent="spinTax">Spin Now</button>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span><br>
		</form>
	</div>
</template>

<script>
	import Error from './../errors/Error.vue';
	import SeparateParagraph from './SeparateParagraph.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';

	export default {
		props: [ 'user', 'token' ],
		components: { Error, SeparateParagraph },
		mixins: [ CrudMixin ],
		data() {
			return {
				wordsMax: 1800,
				count: 0,
				paragraph: '',
				paragraphs: [],
				result: {},
				isValidationFail: false,
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
				this.isLoading = true;
				this.isValidationFail = false;

				axios.post('/words', this.spin)
				.then(response => {
					let data = response.data;

					this.isValidationFail = data.isError;

					// check if validation fail
					if (this.isValidationFail === true) {
						this.errors = data.errors;
						this.isValidationFail = true;
						this.isLoading = false;
					} else {
						this.isValidationFail = false;
						this.isLoading = false;

						// check if api response is success
						if (data.status === 'Success') {
							let text = data.text;
							let paragraphs = text.split(/\n\n\n/); // regex expression finding new line

							this.result = text;
							this.isSuccess = true;

							// display finish paragraph article
							this.generateParagraph(paragraphs);

							// post article
							this.spin['article'] = data.text;
							this.postSpinTax(this.spin);

							// scroll window to top
							/*$('html, body').animate({
								scrollTop: $('div.Word__result').find('h3').offset().top + 'px'
							}, 1000);*/
						}
						
						// check if api response is fail
						if (data.status === 'Failure') {
							this.errors = data.error;
							console.log(data.error);
						}
					}
				});
			},
			postSpinTax(data) {
				axios.post('/words/postSpinTax', data).then(response => console.log(response.data));
			},
			generateParagraph(paragraphs) {
				axios.post('/words/generateParagraph', { paragraphs: paragraphs }).then(response => this.paragraphs = response.data);
			},
			respinParagraph(payload) {
				console.log(payload);
				this.paragraphs[payload.index] = payload.paragraph;
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

