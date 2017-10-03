<template>
    <div class="overlay">
        <div class="AdminDisapprove">
            <h2>Disapprove Article</h2>
            <span>
                Article: &nbsp; <em>"{{ article.doc_title }}"</em>
            </span><hr>

            <form method="POST">
                <!-- <input type="hidden" name="_token" :value="token"> -->

                <div class="form-group">
                    <label for="approveType">Reason Why Decline</label>
                    <select class="form-control" v-model="form.approveType">
                        <option value="select">SELECT A REASON</option>
                        <option v-for="reason in disapproveObj.reason" :value="reason">{{ reason.toUpperCase() }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="approveType">Your Comment</label>
                    <textarea class="form-control" rows="10" v-model="form.comment"></textarea>
                </div>

                <button type="button" class="btn btn-success" @click.prevent="disApproveArticle">Submit</button>
                <button type="button" class="btn btn-danger" @click.prevent="onCancel">Cancel</button>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['article'],
        data() {
            return {
                form: new Form({
                    approveType: 'select',
                    comment: '',
                    word_id: 0
                }),
                disapproveObj: {
                    reason: ['Too Long', 'Too Short', 'Wrong Content', 'Poor Quality', 'Impropper Grammar']
                }
            }
        },
        methods: {
            disApproveArticle() {
                if (this.form.approveType !== 'select') {
                    this.form['word_id'] = this.article.id;
                    this.form.patch('/admin/disApproveArticle').then(data => {
                        if (data) {
                            this.$emit('onSuccessSubmit');

                            // notify user
                            let title = this.article.doc_title;
                            new Noty({
                                type: 'info',
                                text: `<b>${title}</b> article has disapproved.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();
                        }
                    });
                } else {
                    // notify user
                    new Noty({
                        type: 'error',
                        text: `Please select reason why decline.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
                }
            },

            onCancel() {
                this.$emit('isCancel');
            }
        }
    }
</script>

<style scoped>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 99999;
    }

    .AdminDisapprove {
        margin: 7em auto;
        width: 400px;
        background: #fff;
        padding: 1em;
    }

    em { font-size: 1.3em; }
</style>