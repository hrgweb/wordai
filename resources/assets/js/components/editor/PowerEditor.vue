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

			updateSpintaxArticle() {
				const data = {
					word_id: this.article.id,
					spintax: $('div.note-editable').first().html()
				};

				this.isLoading = true;
				this.$refs.changesBtn.disabled = true;

				axios.patch('/words/updateSpintaxArticle', data).then(response => {
					let data = response.data;

					this.isLoading = false;
					this.$refs.changesBtn.disabled = false;

					if (data) {
						this.$emit('isPowerEditorDismiss', { spintax: data.spintax_copy });  // close the power editor component
						ArticleBus.$emit('editorUpdatedSpintaxCopy', { spintax: data.spintax_copy });
					}
				});
			}
		}
	}
</script>