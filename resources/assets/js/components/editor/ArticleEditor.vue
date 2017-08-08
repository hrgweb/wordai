<template>
	<div class="ArticleEditor">
		<!-- <div class="Spintax__result">
			<h3>Spintax Result</h3>
			<p>{{ article.spintax }}</p>
		</div> -->

		<div class="Process__article">
			<h3>Processed Article</h3>
			<div id="editor"></div>

			<div class="Actions">
			    <button type="button" class="btn btn-danger" @click="updateArticle">Update Article</button>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['article'],
		mounted() {
			$('div#editor').summernote('editor.insertText', this.article.spin);
		},
		methods: {
			updateArticle() {
				const data = { 
					id: this.article.id,
					article: $('div.note-editable').html() 
				};

				axios.patch('/editor/updateArticle', data).then(response => {
					let data = response.data;

					if (data.isSuccess) {
						this.$emit('isUpdated', { article: data.result });
					}
				});
			}
		}
	}
</script>

<style scoped>
	.ArticleEditor { margin-bottom: 3em; }
	h3 { text-align: center; }
	p { white-space: pre-wrap; }
</style>