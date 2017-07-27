export const ArticleMixin = {
	data() {
		return {
			copyscape: {},
			responseSuccess: false,
			error: '',
			isError: false,
			duplicates: [],
			smArticle: '', // sm - summernote,
			textgear: {},
			isGrammarTrue: false,
			articleDuplicates: [],
			isDisableSpinAndCs: true
		}
	},
	watch: {
		articleDuplicates(data) {
			this.isDisableSpinAndCs = data.length > 0 ? false : true;
		}
	},
	methods: {
		generateRespintax(index=0) {
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

		splitResultBySentence(results) {
			let duplicates = [];

			for (let i=0; i<results.length; i++) {
				// duplicates.push(results[i].textsnippet.split(/(\s?)\.\.\.(\s?)/gi));
				duplicates.push(results[i].textsnippet.match( /[^\.!\?]+/gi ));
			}

			return duplicates;
		},

		removeEmptyValueFromSentence(duplicates) {
			let finds = [];

			for (let i=0; i<duplicates.length; i++) {
				let firstArr = duplicates[i]; 	// index
				let secondArr = []; 			//value

				for (let j=0; j<firstArr.length; j++) {
					secondArr = firstArr[j].trim();

					// if second array value is not empty
					if (secondArr.length > 3 && /[^\d]/.test(secondArr) && $.inArray(secondArr, finds) === -1) {
						finds.push(secondArr);
					}
				}
			}

			return finds;
		},

		escapeRegExp(str) {
			return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
		},

		setMarkTag(article, find) {
			return article.replace(RegExp(find, 'gi'), '<mark>' + find + '</mark>');
		},

		replaceSearchSenteceByMarkTag(finds) {
			let article = $('div.note-editable').find('p')[0].innerText;

			for (let i=0; i<finds.length; i++) {
				find = this.escapeRegExp(finds[i]);
				article = this.setMarkTag(article, find);
			}

			return article;
		},

		prependMarkTagToSearchSendtenceAndHighlight(finds) {
			let find = '';
			let article = '';

			// replace search sentence by mark tag
			article = this.replaceSearchSenteceByMarkTag(finds);

			// highlight summernote paragraph
			this.smArticle = article;
			$('div.note-editable').find('p').html(this.smArticle);
		},

		colorDuplicatesInRed(duplicates) {
			let paragraphs = $('div.Copyscape__result').find('p');
			let paragraph = '';
			let vm = this;

			$.each(paragraphs, function(index, value) {
				paragraph = value.innerText.trim();

				for (let i=0; i<duplicates.length; i++) {
					paragraph = vm.setMarkTag(paragraph, vm.escapeRegExp(duplicates[i]));
				}

				// modify result of the copyscape[index].result.textsnippet
				vm.copyscape.result[index].textsnippet = paragraph;
				
				// when search color red the duplicate
				paragraphs.html(paragraph);
			});
		},

		copyScapeData(data) {
			let results = data;
			let duplicates = [];
			let finds = [];

			// split to sentence 
			duplicates = this.splitResultBySentence(results);

			// remove empty value from duplicates
			finds = this.removeEmptyValueFromSentence(duplicates);

			// prepend mark tag to search string and highlight
			this.prependMarkTagToSearchSendtenceAndHighlight(finds);

			// replace duplicates and color by red
			Vue.nextTick(() => this.colorDuplicatesInRed(finds));

			// store as result in vue data
			this.duplicates = finds;
		},

		copyScapeSetup(url, data) {
			this.isLoading = true;
			this.isError = false;
			this.$refs.csButton.disabled = true;

			axios.post(url, data).then(response => {
				let data = response.data;

				// api result response success
				this.isLoading = false;
				this.copyscape = data;
				this.responseSuccess = true;
				this.$refs.csButton.disabled = false;

				// find all duplicate occurences
				this.copyScapeData(data.result);

				// check if api response is fail
				if (data.error) {
					this.error = data.error;
					this.isError = true;
					this.responseSuccess = false;
				}
			});
		},

		processToCopyscape() {
			this.spin['type'] = this.type;
			let url = '/words/processToCopyscape';

			// if type is 'article' then it is entire article
			// else separate paragprah
			switch(this.type) {
				case 'article':
					this.spin['article'] = (! this.responseSuccess) ? this.article : $('div.note-editable').text();
					this.copyScapeSetup(url, this.spin);
					break;
				case 'paragraph':
					this.spin['paragraph'] = this.paragraph;
					this.copyScapeSetup(url, this.spin);
					break;
			}
		},

		processToTextGear() {
			this.isLoading = true;
			this.isError = false;
			this.$refs.tgButton.disabled = true;

			const data = { text: $('div.note-editable').text() };

			axios.post('/words/processTextGrammar', data).then(response => {
				let data = response.data;

				if (data.result) { // api result response success
					this.isLoading = false;
					this.textgear = data;
					this.isGrammarTrue = true;
					this.$refs.tgButton.disabled = false;
				} else { // check if api response is fail
					this.error = data.error;
					this.isError = true;
					this.isGrammarTrue = false;
				}
			});
		},

		updateDuplicates(duplicates) {
			this.articleDuplicates = duplicates;
		},

		generateNewArticle(article) {
			axios.post('/words/generateFullArticle', { article: article }).then(response => {
				this.article = response.data;
				this.isSuccess = true;
			});
		},

		spinDuplicatesAndCopyscape() {
			this.isLoading = true;
			this.$refs.spinAndCsButton.disabled = true;

			// join the duplicates e.g ['a===b===c']
			let joinDuplicates = this.articleDuplicates.join('===');

			// data for spin
			this.spin['article'] = joinDuplicates;
			this.spin['type'] = 'article-duplicates';

			// run spintax and spin
			axios.post('/words', this.spin)
				.then(response => {
					let data = response.data;

					this.isLoading = false;
					this.$refs.spinAndCsButton.disabled = false;

					// check if api response is success
					if (data.status === 'Success') {
						let text = data.text;

						// this.spintaxResult = text;
						console.log(text);

						// article is now the spintax result
						// display finish full article
						this.generateNewArticle(this.spin.article);
					}
					
					// check if api response is fail
					if (data.status === 'Failure') {
						this.error = data.error;
					}
				});

			// make new array of duplicates base on join results
			// results of article duplicates
			// search for the duplicates in the article
			// if find then replace with the new array of join results
			// run copyscape
		}
	}
};