<template>
	<div class="Peditor">
		<div id="peditor"></div>

        <button type="button" class="btn btn-danger" @click="dissmissSpintaxArticle">Dismiss</button>
	</div>
</template>

<script>
	export default {
		props: ['article'],
		data() {
			return {
			
			}
		},
		mounted() {
			$('div#peditor').summernote('editor.insertText', this.article.spintax);
		},
		methods: {
			dissmissSpintaxArticle() {
				this.$emit('isPowerEditorDismiss');
			}
		}
	}
</script>