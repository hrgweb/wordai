class UserLevel {

	level(level) {
		let lev = '';

		switch(level) {
			case 1:
				lev = 'Manager';
				break;
			case 2:
				lev = 'Admin';
				break;
			case 3:
				lev = 'Editor';
				break;
			case 4:
				lev = 'Writer';
				break;
			case 5:
				lev = 'Curator';
				break;
			case 6:
				lev = 'Proofing';
				break;
			case 7:
				lev = 'Poster';
				break;
		}

		return lev;
	}
}	

export default UserLevel;