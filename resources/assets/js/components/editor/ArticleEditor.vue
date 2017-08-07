<template>
	<div class="ArticleEditor">
		<div class="Spintax__result">
			<h3>Spintax Result</h3>
			<p>{{ article.spintax }}</p>
		</div>

		<div class="Process__article">
			<h3>Processed Article</h3>
			<div id="editor"></div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['article'],
		mounted() {
			$('div#editor').summernote('editor.insertText', this.article.spin);
		}
	}
</script>

<style scoped>
	.ArticleEditor { margin-bottom: 3em; }
	h3 { text-align: center; }
	p { white-space: pre-wrap; }
</style>