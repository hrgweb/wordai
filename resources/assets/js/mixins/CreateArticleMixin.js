export const CreateArticleMixin = {
    methods: {
        resetInputFields() {
            this.spin['article'] = $('textarea#article').val('');
            this.spin['article_type_id'] = 'select';
            this.spin['doc_title'] = '';
            this.spin['keyword'] = '';
            this.spin['lsi_terms'] = '';
            this.spin['domain_protected'] = '';
        },

        saveArticle() {
            // check if domain_id is set
            if (this.spin.domain_id !== 'select') {
                this.isLoading = true;
                this.isValidationFail = false;
                this.$refs.spinButton.disabled = true;

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
                new Noty({
                    type: 'warning',
                    text: `Please select domain name.`,
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