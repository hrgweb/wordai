<template>
	<div class="Word">
		<div class="Word__result" v-show="isSuccess">
			<!-- Full Article -->
			<full-article
				:token="token"
				:spintaxResult="spintaxResult"
				:spin="spin"
				:article="article"
				:type="spintaxType = 'article'"
				v-if="isSuccess">
			</full-article><br>
		</div>

		<h1>Article</h1><hr>
		
		<form method="POST" @submit.prevent="spinTax">
			<input type="hidden" name="_token" :value="token">

			<!-- <textarea class="form-control" rows="8" :maxlength="wordsMax" v-model="words" @keyup="wordCount"></textarea> -->
			<label for="article">Original Article</label>
			<textarea class="form-control" rows="40" v-once v-model="spin.article" @keyup="wordCount"></textarea>
			<br>

			<!-- Erorr component -->
			<error 
				:type="errorType"
				:list="errors"
				v-if="isValidationFail">
 			</error>
			<br>

			<button type="submit" class="btn btn-primary">Spin Now</button>
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span><br>
		</form>
	</div>
</template>

<script>
	import Error from './../errors/Error.vue';
	import SeparateParagraph from './SeparateParagraph.vue';
	import CopyscapeApi from './CopyscapeApi.vue';
	import FullArticle from './FullArticle.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';

	export default {
		props: [ 'user', 'token' ],
		components: { Error, SeparateParagraph, FullArticle, CopyscapeApi },
		mixins: [ CrudMixin ],
		data() {
			return {
				article: '',
				wordsMax: 1800,
				count: 0,
				paragraph: '',
				paragraphs: [],
				spintaxResult: '',
				isValidationFail: false,
				errorType: 1, // legend: 1-input validation, 0-fail response result from server
				spintaxType: 'article',
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

				/*let text = `
					How To Apply For Social Security Disability
					The first step in how to apply for social security disability is to contact the Social Security Administration to schedule your disability interview. You may contact your local Social Security office by telephone, or make an office visit, or you can call the toll free Social Security number to have a disability claim taken or scheduled for you at your local office. 
					For those who are unclear about the differences between SSD and SSI, Social Security administers two disability programs -- Social Security disability (SSDI) and Supplemental Security Income (SSI). Social Security disability is based upon insured status, which is achieved through your work activity. Supplemental Security Income is a need-based program that does not depend upon your work history. SSI is based upon your income or resources. 
					Where do I go to apply?
				`;
				let key = 'SzFohpMVhgmvbyRx';
				let url = 'https://api.textgears.com/check.php?text='+text+'&key='+key;
				
				axios.post('/words/processTextGrammar', { text: text, key: key })
					.then(response => {
						let data = response.data;

						console.log(data);
					});*/

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

								this.spintaxResult = text;

								// article is now the spintax result
								// display finish full article
								this.generateFullArticle(this.spin.article);

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
								this.isValidationFail = true;
								this.errors = data.error;
							}
						}
					});
			},

			postSpinTax(data) {
				axios.post('/words/postSpinTax', data).then(response => console.log(response.data));
			},

			generateFullArticle(article) {
				axios.post('/words/generateFullArticle', { article: article }).then(response => {
					this.article = response.data;
					this.isSuccess = true;
				});
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