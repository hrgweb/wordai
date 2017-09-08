import Vue from 'vue';

export const WordaiBus = new Vue({
    data() {
        return {
            articles: [],
            keywords: [],
            isKeywordExist: false
        };
    },

    watch: {
        articles(data) {
            return data;
        },

        keywords(data) {
            return data;
        }
    },

    methods: {
        getKeywordsAssociatedByDomain(domain_id) {
            axios.get('/words/getKeywordsAssociatedByDomain?domain_id='+domain_id)
                .then(response => this.keywords = response.data);
        }
    }
});