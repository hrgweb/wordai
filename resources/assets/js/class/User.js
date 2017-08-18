class User {

	getUserId(vm, options, searchText) {
		let result = $.grep(options, function(item, index) {
			let optionVal = options[index].value;

			return (optionVal === searchText);
		});

		return result[0];
	}
}

export default User;