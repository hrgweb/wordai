class UserStatus {

	status(status) {
		let stats = '';

		switch(status) {
			case 1:
				stats = 'Active';
				break;
			case 2:
				stats = 'Waiting Approval';
				break;
			case 3:
				stats = 'Suspended';
				break;
		}

		return stats;
	}
}	

export default UserStatus;