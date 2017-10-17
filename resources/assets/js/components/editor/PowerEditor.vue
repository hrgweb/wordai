<template>
	<div class="Peditor">
		<div id="peditor"></div>

        <button type="button" class="btn btn-success" @click="updateSpintaxArticle" ref="changesBtn">Save Spintax Changes</button>
        <button type="button" class="btn btn-danger" @click="dissmissSpintaxArticle">Dismiss</button>

        <!-- Misc -->
        &nbsp;&nbsp;&nbsp;
		<span v-if="isLoading">LOADING....</span>
	</div>
</template>

<script>
	export default {
		props: ['article', 'inputs', 'tableType'],
		data() {
			return {
                isLoading: false,
                newArticle: {},
                hasReplaceVars: false
            };
		},
		mounted() {
            this.newArticle = this.article;

			this.initSpintax();
		},
		methods: {
            initSummernote(spintax) {
                // summernote insert text
                $('div#peditor').summernote('editor.insertText', spintax);

                // format 1st summernote
                Vue.nextTick(() => $('div.Peditor').find('p').html(spintax));
            },

			initSpintax() {
                let article = this.article;

				if (article.isEditorUpdateSC === 1) {
                    this.initSummernote(article.spintax_copy);
                } else {
                    this.initSummernote(article.spintax);
                }
			},

			dissmissSpintaxArticle() {
				this.$emit('isPowerEditorDismiss');
			},

            removeSpan(spintax) {
                let finds = ['<span class="curly">', '<span class="pipe">', '</span>'];

                return spintax.replace(finds[0], function(char, offset, string) {
                    let span = '';

                    if (char.match(finds[0])) {
                        span = ''
                    }

                    return span;
                });

                /*for (var i = 0; i < finds.length; i++) {
                    let result = spintax.replace(new RegExp(finds[i]), function(char, offset, string) {
                        let span = '';

                        if (char.match(new RegExp(finds[i])) {
                            span = '<span class="curly">' + char + '</span>'
                        } else {
                            span = '<span class="pipe">' + char + '</span>'
                        }

                        return span;
                    });
                }*/
            },

            protectedVars(article, terms) {
                let finds = ['%company%', '%city%', '%state%'];
                let vars = [];

                for (var i = 0; i < finds.length; i++) {
                    if (article.indexOf(finds[i]) >= 0) {
                        vars.push(finds[i]);
                    }
                }

                // combine result of vars and protected terms into 1 array
                let protected_terms = vars.concat(terms);


                return protected_terms.join();
            },

            replaceVarsWithData(article, vars) {
                let finds = ['%company%', '%city%', '%state%'];
                let counter = 0;

                for (var i = 0; i < finds.length; i++) {
                    if (article.indexOf(finds[i]) >= 0) {
                        article = article.replace(finds[i], vars[i]);
                        counter++;
                    }
                }

                // check if has replace one value or more to vars
                this.hasReplaceVars = counter > 0 ? true : false;

                return article;
            },

			updateSpintaxArticle() {
                // this.article['spintax'] = $('div.Peditor').find('div.note-editable').first().text();

                this.newArticle['spintax'] = $('div.Peditor').find('div.note-editable').first().text();
                this.newArticle['input'] = this.inputs;
                // this.newArticle['clickType'] = clickType;

				this.isLoading = true;
				this.$refs.changesBtn.disabled = true;

                let input = this.newArticle.input;
                let vars = [input.company, input.city, input.state];

                // replace vars values
                this.newArticle['spintax'] = this.replaceVarsWithData(this.newArticle.spintax, vars);

                // check if has replace value from vars
                // if (this.hasReplaceVars) {
                    axios.patch('/words/updateSpintaxArticle', this.newArticle).then(response => {
                        let data = response.data;

                        this.isLoading = false;
                        this.$refs.changesBtn.disabled = false;

                        if (data) {
                            this.$emit('isPowerEditorDismiss');  // close the power editor component
                            ArticleBus.$emit('editorUpdatedSpintaxCopy', data);

                            // successfully updated
                            new Noty({
                                type: 'info',
                                text: `1 spintax article successfully updated.`,
                                layout: 'bottomLeft',
                                timeout: 5000
                            }).show();
                        }
                    });
                /*} else {
                    new Noty({
                        type: 'error',
                        text: `Nothing replace can't find placeholder. Please update yor article and add placeholder to change.`,
                        layout: 'bottomLeft',
                        timeout: 5000
                    }).show();
                }*/
			}
		}
	}
</script>