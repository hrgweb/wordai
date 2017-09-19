import Error from './../components/errors/Error.vue';
import SeparateParagraph from './../components/words/SeparateParagraph.vue';
import CopyscapeApi from './../components/words/CopyscapeApi.vue';
import FullArticle from './../components/words/FullArticle.vue';
import User from './../class/User.js';

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
				article_type_id: 'select',
				doc_title: '',
				domain_id: 0,
				domain: '',
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
			isDomainNotSet: false,
			userObj: new User()
		}
	},
	created() {
		this.authUser = JSON.parse(this.user);

		this.listOfArticleType();
		this.userDomainList();
		this.userDomainSetup();
	},
	watch: {
		articleTypes(data) {
			this.isArticleTypesLoaded = data.length > 0 ? true : false;
		},

		article(data) {
			this.spin['spin'] = data;
			this.postSpinTax(this.spin); // post article
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

			axios.post('/words', this.spin).then(response => {
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
						this.spin['article'] = this.spin.article;
						this.spin['spintax'] = text;
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

        setupDomainChange(url) {
            axios.get(url).then(response => {
                let data = response.data;

                if (data) {
                    this.domainFillIn(false, data.protected, data.synonym);
                } else {
                    this.domainFillIn(true, '', '');
                }
            });
        },

		domainChange() {
			let vm = this;
			// let options = $('datalist#domains').find('option');
			// let domain_id = this.userObj.getUserId(vm, options, vm.spin.domain);
            // this.spin.domain_id = domain_id.attributes[1].value;

            let domain_id = this.spin.domain_id;
			let url = '/words/domainChange?domain_id=' + domain_id;

			if (domain_id > 0) {
				this.setupDomainChange(url);
                this.wordaiBus.getKeywordsAssociatedByDomain(domain_id);
			} else {
				this.spin['protected'] = '';
				this.spin['synonym'] = '';
			}
		},

		userDomainSetup() {
			axios.get('/user/userDomainSetup').then(response => {
				let data = _.head(response.data);

				if (data) {
					this.spin['domain'] = data.domain;
					this.spin['domain_id'] = data.domain_id;
					this.spin['protected'] = data.protected;
					this.spin['synonym'] = data.synonym;
				}
			});
		},

        userDomainList() {
            let user_id = this.authUser.id;
            axios.get('/user/userDomainList?user_id='+user_id).then(response => this.domains = response.data);
        }
	}
};