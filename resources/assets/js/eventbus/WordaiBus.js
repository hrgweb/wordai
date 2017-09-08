import Vue from 'vue';
import Form from './../class/Form.js';

export const WordaiBus = new Vue({
    data() {
        return {
            form: new Form(),
            articles: []
        };
    },
    watch: {
        articles(data) {
            return data;
        }
    },
    mounted() {
        this.listOfArticles();
    },
    methods: {
        listOfArticles() {
            axios.get('/words/listOfArticles')
                .then(response => {
                    let data = response.data;

                    if (data) {
                        data = data.map(item => {
                            return {
                                word_id: item.word_id,
                                domain_id: item.domain_id,
                                keyword: item.keyword.length > 0 ? item.keyword.toLowerCase().trim() : ''
                            };
                        });

                        this.articles = data;
                    }

                });
        }
    }
});