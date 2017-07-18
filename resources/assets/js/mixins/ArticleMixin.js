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

		prependMarkTagToSearchSendtenceAndHighlight(finds) {
			let article = $('div.note-editable').find('p')[0].innerText;
			let find = '';

			for (let i=0; i<finds.length; i++) {
				find = this.escapeRegExp(finds[i]);

				article = article.replace(RegExp(find, 'gi'), '<mark>' + find + '</mark>');
				// console.log(article);
			}

			// highlight summernote paragraph
			this.smArticle = article;
			$('div.note-editable').find('p').html(this.smArticle);
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