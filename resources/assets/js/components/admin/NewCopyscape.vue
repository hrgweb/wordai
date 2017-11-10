<template>
    <div class="NewCopyscape">
        <!-- Input -->
        <form method="POST">
            <!-- <input type="hidden" name="_token" :value="token"> -->

            <div class="form-group">
                <label for="article">Article</label>
                <textarea class="form-control" rows="12" v-model="form.article"></textarea>
            </div>



            <button type="button" class="btn btn-primary" @click.prevent="onSubmit">Copyscape</button>
        </form>

        <!-- Loading -->
        &nbsp;&nbsp;&nbsp;
        <span v-if="isLoading">LOADING....</span>
        <span v-if="isError" style="color: red;">{{ error }}</span><br>

        <!-- Result -->
        <prepost-result
            :prepost="prepost"
            v-if="hasPrepost"
        >
        </prepost-result>
    </div>
</template>

<script>
    import PrepostResult from './../words/PrepostResult.vue';

    export default {
        components: { PrepostResult },
        data() {
            return {
                prepost: {},
                error: '',
                hasPrepost: false,
                isLoading: false,
                isError: false,
                form: new Form({
                    article: ''
                })
            }
        },
        methods: {
            onSubmit() {
                this.hasPrepost = false;
                this.isLoading = true;
                
                this.form.post('/words/processToNewCopyscape').then(data => {
                    let account = JSON.parse(data.account);
                    let response = JSON.parse(data.response);

                    this.hasPrepost = true;
                    this.isLoading = false;

                    // check if server is failed
                    if (response.hasOwnProperty('error')) {
                        this.isError = true;
                        console.log('has error');
                    } else {
                        this.isError = false;
                        this.prepost = response;
                        console.log('no error');
                    }

                    console.log(account);
                    console.log(response)
                })
            }
        }
    }
</script>