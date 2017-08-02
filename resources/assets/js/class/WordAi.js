class WordAi {

	protectedTermsSetup(terms) {
		let result = '';

		// find match of not ','
		result = terms.match(/[^\,]+/g);

		// trim space in every match result
		result = result.map(word => {
			let newStr = '';

			newStr += word.trim();

			return newStr;
		});

		// result
		return result.join(',');
	}
}

export default WordAi;