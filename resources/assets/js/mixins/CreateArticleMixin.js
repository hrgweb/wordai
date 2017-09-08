import { WordaiBus } from './../eventbus/WordaiBus.js';
// import WordAi from './../class/WordAi.js';

export const CreateArticleMixin = {
    data() {
        return {
            wordaiBus: WordaiBus
        };
    },
    methods: {
        resetInputFields() {
            this.spin['article'] = $('textarea#article').val('');
            this.spin['article_type_id'] = 'select';
            this.spin['doc_title'] = '';
            this.spin['keyword'] = '';
            this.spin['lsi_terms'] = '';
            this.spin['domain_protected'] = '';
        },

        checkIfDomainKeywordExist(keywords, keyword) {
            let isExist = false;

            if (keywords.length > 0 && keyword.length > 0) {
                // check if domain = to spin and also the keyword
                for (var i = 0; i < keywords.length; i++) {
                    if (keywords[i] === keyword) {
                        isExist = true;
                        break;
                    } else {
                        isExist = false;
                    }
                }
            }

            return isExist;
        },

        showNotificationKeywordNotExist() {
            new Noty({
                type: 'info',
                text: `The keyword you enter does not exists yet.`,
                layout: 'bottomLeft',
                timeout: 5000
            }).show();
        },

        saveArticle() {
            // check if domain_id is set and article type
            if (
                this.spin.domain_id > 0 &&
                this.spin.article_type_id != 'select'
            ) {
                this.isLoading = true;
                this.isValidationFail = false;
                // this.$refs.spinButton.disabled = true;

                let keywords = this.wordaiBus.keywords;
                let keyword = this.spin.keyword;

                // check if keyword is exists
                if (keyword.length > 0 && !this.checkIfDomainKeywordExist(keywords, keyword)) {
                    // this.showNotificationKeywordNotExist();
                    this.wordaiBus.isKeywordExist = false;

                    axios.post('/words', this.spin).then(response => {
                        let data = response.data;

                        this.isLoading = false;
                        this.isDomainNotSet = false;
                        this.$refs.spinButton.disabled = false;

                        if (data.isError) { // validation fails
                            this.isValidationFail = true;
                            this.errorType = 1;
                            this.errors = data.errors;
                        } else { // validation success
                            this.isValidationFail = false;

                            // notify user article posted successfully
                            let articleTitle = this.spin.doc_title;
                            new Noty({
                                type: 'success',
                                text: `<b>${articleTitle}</b> article successfully saved.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();

                            // reset spin values
                            this.resetInputFields();

                            // animate div to top
                            $('html, body').animate({ scrollTop: 0 });
                        }
                    });
                } else {
                    this.isLoading = false;

                    if (keyword.length <= 0) {
                        new Noty({
                            type: 'error',
                            text: `Keyword is required.`,
                            layout: 'bottomLeft',
                            timeout: 5000
                        }).show();
                    } else {
                        this.wordaiBus.isKeywordExist = true;
                    }
                }
            } else {
                new Noty({
                    type: 'error',
                    text: `Domain name and Article Type are required.`,
                    layout: 'bottomLeft',
                    timeout: 5000
                }).show();
            }
        },

        saveAndProcessNow() {
            // check if domain_id is set
            if (this.spin.domain_id !== 'select') {
                this.isLoading = true;
                this.isValidationFail = false;
                this.$refs.spinButton.disabled = true;

                axios.post('/words/saveAndProcessNow', this.spin).then(response => {
                    let data = response.data;

                    this.isLoading = false;
                    this.isDomainNotSet = false;
                    this.$refs.spinButton.disabled = false;

                    if (data.isError) { // validation fails
                        this.isValidationFail = true;
                        this.errorType = 1;
                        this.errors = data.errors;
                    } else { // validation success
                        this.isValidationFail = false;

                        // reset spin values
                        /*this.resetInputFields();

                        // notify user article posted successfully
                        let articleTitle = this.spin.doc_title;
                        new Noty({
                            type: 'success',
                            text: `<b>${articleTitle}</b> article successfully saved.`,
                            layout: 'bottomLeft',
                            timeout: 5000
                        }).show();*/

                        if (data.isError) { // validation fails
                            this.isValidationFail = true;
                            this.errorType = 0;
                            this.errors = data.errors;
                        }

                        console.log(data);
                    }
                });
            } else {
                new Noty({
                    type: 'warning',
                    text: `Please select domain name.`,
                    layout: 'bottomLeft',
                    timeout: 5000
                }).show();
            }
        }
    }
};