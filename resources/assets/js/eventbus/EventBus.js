import Vue from 'vue';

export const EventBus = new Vue({
	data() {
		return {
			isEditorEditBtnDisable: false,
            /*reportingFilter: {
                groupBy: 'select',
                orderBy: 'date',
                sortBy: 'asc'
            }*/
		};
	}
});