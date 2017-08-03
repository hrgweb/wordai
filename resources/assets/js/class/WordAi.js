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

	protectedTermsToUppercase(terms, uCase) {
		let result = terms.split(/\,/g);

		result = result.map(word => {
			let output = '';

			output += word[uCase]();

			return output;				
		});

		return result.join(',');
	}

	protectedTermsToLowercase(terms, lCase) {
		this.protectedTermsToUppercase(terms, lCase);
	}

	protectedTermsToSentenceCase(terms) {
		let n = terms.split(/[\,\.]/g);
		let vfinal = "";

		for (let i = 0; i<n.length; i++)
		{
			let spaceput = "";
			let spaceCount = n[i].replace(/^(\s*).*$/,"$1").length;

			n[i] = n[i].replace(/^\s+/,"");

			let newstring = n[i].charAt(n[i]).toUpperCase() + n[i].slice(1);

			for (let j = 0; j < spaceCount; j++)

			spaceput = spaceput + " ";
			vfinal = vfinal + spaceput + newstring + ",";
		 }

		 vfinal = vfinal.substring(0, vfinal.length - 1);

		 return vfinal;
	}

	domainSelectedIndex(options) {
		let selectedIndex = 0;

		for (let i=0; i<options.length; i++) {
			if (options[i].selected === true) {
				selectedIndex = options[i].index;
				break;
		    }
		}

		return selectedIndex;
	}
}

export default WordAi;