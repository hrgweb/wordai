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
		props: ['article'],
		data() {
			return { isLoading: false };
		},
		mounted() {
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

			updateSpintaxArticle() {
                this.article['spintax'] = $('div.Peditor').find('div.note-editable').first().text();

				this.isLoading = true;
				this.$refs.changesBtn.disabled = true;

				axios.patch('/words/updateSpintaxArticle', this.article).then(response => {
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
			}
		}
	}
</script>