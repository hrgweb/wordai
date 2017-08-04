import Error from './../components/errors/Error.vue';
import SeparateParagraph from './../components/words/SeparateParagraph.vue';
import CopyscapeApi from './../components/words/CopyscapeApi.vue';
import FullArticle from './../components/words/FullArticle.vue';

export const ArticleActionMixin = {
	props: [ 'user', 'token' ],
	components: { Error, SeparateParagraph, FullArticle, CopyscapeApi },
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
				articleType: 'select',
				doc_title: '',
				domain_id: 'select',
				keyword: '',
				lsi_terms: '',
				domain_protected: '',
				article: '',
				protected: '',
				synonym: ''
			},
			articleTypes: [],
			isCurated: false,
			isArticleTypesLoaded: false,
			domains: [],
			isDomainNotSet: false
		}
	},
	created() {
		this.authUser = JSON.parse(this.user);

		this.listOfArticleType();
		this.domainList();
	},
	watch: {
		articleTypes(data) {
			this.isArticleTypesLoaded = data.length > 0 ? true : false;
		}
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
			this.isSuccess = false;
			this.$refs.spinButton.disabled = true;

			this.spin['article_type_id'] = this.articleType;
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
					this.$refs.spinButton.disabled = false;

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
					}
					
					// check if api response is fail
					if (data.status === 'Failure') {
						this.errorType = 0;
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
		},

		listOfArticleType() {
			axios.get('/articleType/listOfArticleType').then(response => this.articleTypes = response.data);
		},

		domainList() {
			axios.get('/admin/domainList').then(response => this.domains = response.data);
		},

		domainFillIn(isSet, term, synonym) {
			this.isDomainNotSet = isSet;
			this.spin['protected'] = term;
			this.spin['synonym'] = synonym;
		},

		domainChange() {
			let url = '/words/domainChange?domain_id=' + this.spin.domain_id;

			if (this.spin.domain_id > 0) axios.get(url).then(response => {
				let data = response.data;

				if (data) {
					this.domainFillIn(false, data.protected, data.synonym);
				} else {
					this.domainFillIn(true, '', '');
				}
			});
		}
	}

};