import Vue from 'vue';
import axios from 'axios';

export const DomainBus = new Vue({
    data() {
        return {
            domains: [],
            isAdmin: false,
            user: {},
            isEdit: false,
            raw: {},
            index: 0
        };
    },

    watch: {
        domains(data) {
            return data;
        },

        user(data) {
            this.isAdmin = data.user_level_id === 2 ? true : false;
        }
    },

    created() {
        this.domainList();
    },

    methods: {
        domainList() {
            axios.get('/admin/domainList').then(response => this.domains = response.data);
        },

        displayDomainEdit(url, index) {
            this.isEdit = true;
            this.index = index;
            this.raw = url;
        },
    }
});