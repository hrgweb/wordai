<template>
	<div class="Peditor">
		<div id="peditor"></div>

        <button type="button" class="btn btn-success" @click="updateSpintaxArticle">Save Spintax Changes</button>
        <button type="button" class="btn btn-danger" @click="dissmissSpintaxArticle">Dismiss</button>
	</div>
</template>

<script>
	export default {
		props: ['article'],
		mounted() {
			this.initSpintax();
		},
		methods: {
			initSpintax() {
				let article = this.article;
				let div = $('div#peditor');

				if (article.isEditorUpdateSC === 1) {
					div.summernote('editor.insertText', article.spintax_copy);
				} else {
					div.summernote('editor.insertText', article.spintax);
				}
			},

			dissmissSpintaxArticle() {
				this.$emit('isPowerEditorDismiss');
			},

			updateSpintaxArticle() {
				const data = {
					word_id: this.article.id,
					spintax: $('div.note-editable').first().text()
				};

				axios.patch('/words/updateSpintaxArticle', data).then(response => {
					let data = response.data;

					if (data) {
						this.$emit('isPowerEditorDismiss');  // close the power editor component
						EventBus.$emit('editorUpdatedSpintaxCopy', { spintax: data.spintax_copy });
					}
				});
			}
		}
	}
</script>