export const ArticleMixin = {
	data() {
		return {
			copyscape: {},
			responseSuccess: false,
			error: '',
			isError: false,
			duplicates: [],
			smArticle: '' // sm - summernote
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
					if (secondArr.length > 0 && /[^\d]/.test(secondArr) && $.inArray(secondArr, finds) === -1) {
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
			Vue.nextTick(() => this.colorDuplicatesInRed(finds) );

			// store as result in vue data
			this.duplicates = finds;
		},

		copyScapeSetup(url, data) {
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
			this.isLoading = true;
			this.isError = false;
			this.$refs.csButton.disabled = true;

			this.spin['type'] = this.type;
			let url = '/words/processToCopyscape';

			// if type is 'article' then it is entire article
			// else separate paragprah
			switch(this.type) {
				case 'article':
					this.spin['article'] = this.article;
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

			let key = 'SzFohpMVhgmvbyRx';
			// let url = 'https://api.textgears.com/check.php?text='+this.paragraph+'&key='+key;

			axios.post(url, { text: this.paragraph, key: key }).then(response => {
				let data = response.data;

				// api result response success
				/*this.isLoading = false;
				this.copyscape = data;
				this.responseSuccess = true;

				// check if api response is fail
				if (data.error) {
					this.error = data.error;
					this.isError = true;
					this.responseSuccess = false;
				}*/

				console.log(data);
			});
		}
	}
};