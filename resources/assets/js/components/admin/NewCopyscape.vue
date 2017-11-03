<template>
    <div class="NewCopyscape">
        <form method="POST">
            <!-- <input type="hidden" name="_token" :value="token"> -->

            <div class="form-group">
                <label for="article">Article</label>
                <textarea class="form-control" rows="12" v-model="form.article"></textarea>
            </div>



            <button type="button" class="btn btn-primary" @click.prevent="onSubmit">Copyscape</button>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    article: ''
                })
            }
        },
        methods: {
            onSubmit() {
                this.form.post('/words/processToNewCopyscape').then(data => {
                    let account = JSON.parse(data.account);
                    let response = JSON.parse(data.response);

                    console.log(account);
                    console.log(response)
                })
            }
        }
    }
</script>