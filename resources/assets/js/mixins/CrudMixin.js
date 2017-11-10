export const CrudMixin = {
    data() {
        return {
            isEdit: false,
            isSuccess: false,
            isLoading: false,
            errors: [],
            authUser: {},
            notify: {
                type: true,
                message: 'Domain',
                action: 'saved'
            }
        };
    }
};