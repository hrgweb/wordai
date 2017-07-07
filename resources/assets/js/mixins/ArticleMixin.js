export const ArticleMixin = {
	data() {
		return {
			copyscape: {},
			responseSuccess: false,
			error: '',
			isError: false
		}
	},
	methods: {
		generateRespintax(index) {
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

		copyScapeSetup(data) {
			axios.post('/words/processToCopyscape', data).then(response => {
				let data = response.data;

				// api result response success
				this.isLoading = false;
				this.copyscape = data;
				this.responseSuccess = true;

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

			// if type is 'article' then it is entire article
			// else separate paragprah
			switch(this.type) {
				case 'article':
					this.spin['article'] = this.article;
					this.copyScapeSetup(this.spin);
					break;
				case 'paragraph':
					this.spin['paragraph'] = this.paragraph;
					this.copyScapeSetup(this.spin);
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