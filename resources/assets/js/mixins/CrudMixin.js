export const CrudMixin = {
	data() {
		return {
			isEdit: false,
			isSuccess: false,
			notify: {
				type: true,
				message: 'Domain',
				action: 'saved'
			}
		}
	}
};