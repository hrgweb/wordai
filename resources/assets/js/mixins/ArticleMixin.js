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

		copyScapeData(data) {
			let results = data;
			let duplicates = [];
			let finds = [];

			// split duplicates 
			for (let i=0; i<results.length; i++) {
				duplicates.push(results[i].textsnippet.split(/\.\.\./gi));
				
				// duplicates.push(results[i].textsnippet.match( /[^\.!\?]+[\.!\?]+/g ));
			}

			// this.duplicates = duplicates;

			// remove empty value from duplicates
			for (let i=0; i<duplicates.length; i++) {
				let firstArr = duplicates[i]; 	// index
				let secondArr = []; 			//value

				for (let j=0; j<firstArr.length; j++) {
					secondArr = firstArr[j];

					// if second array value is not empty
					if (secondArr.length > 0) {
						finds.push(secondArr.trim());
					}
				}
			}

			// remove duplicates
			let uniqueSentence = [];

			$.each(finds, function(i, el){
			    if ($.inArray(el, uniqueSentence) === -1) uniqueSentence.push(el);
			});

			this.duplicates = uniqueSentence;

			// prepend mark tag to search string
			let article = $('div.note-editable').find('p')[0].innerText;
			let find = '';
			let specialCharArr = ['!', ',', '?', '.'];

			for (let i=0; i<uniqueSentence.length; i++) {
				// if (i === 1) break;

				find = uniqueSentence[i].trim();
				let lastChar = find.charAt(find.length-1);

				if ($.inArray(lastChar, specialCharArr) !== -1) {
					find = find.slice(0, find.length-1); // slice the duplicate word and remove special chars
				}	

				article = article.replace(RegExp(find, 'gi'), '<mark>' + find + '</mark>');
				// console.log(article);
			}

			// highlight summernote paragraph
			this.smArticle = article;
			$('div.note-editable').find('p').html(this.smArticle);
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