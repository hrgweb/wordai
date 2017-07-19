<template>
	<div class="Article">
		<div class="Spintax__result">
			<h2>Spintax Result</h2>
			<p style="white-space: pre-wrap;">{{ spintaxResult }}</p><br>
		</div>

		<div class="Process__article">
			<h2>Processed Article</h2>
			<form method="POST" role="form">
				<input type="hidden" name="_token" :value="token">
			
				<div id="editor">{{ smArticle }}</div>
				<!-- <textarea class="form-control" rows="40">{{ smArticle }}</textarea> -->
				<br>

				<copyscape-result
					:copy="copyscape"
					v-if="responseSuccess">
	 			</copyscape-result>
			
				<button type="button" class="btn btn-success" @click="generateRespintax">Respin</button>
				<button type="button" class="btn btn-warning" @click="processToCopyscape">Copyscape</button>
				<button type="button" class="btn btn-info" @click="processToTextGear">Check Grammar</button>
				&nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span>
				<span v-if="isError" style="color: red;">{{ error }}</span><br>
			</form>
		</div>
	</div>
</template>

<script>
	import CopyscapeResult from './CopyscapeResult.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import { ArticleMixin } from './../../mixins/ArticleMixin.js';

	export default {
		props: ['token', 'spintaxResult', 'spin', 'type', 'article'],
		components: { CopyscapeResult },
		mixins: [ CrudMixin, ArticleMixin ],
		mounted() {
			this.smArticle = this.article;
			
			// init summernote
			$('div#editor').summernote();

			// highlight summernote paragraph
			$('div.note-editable').find('p').text(this.article);
		}
	}
</script>

<style scoped>
	h2 { text-align: center; }	
</style>