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
					@updateduplicates="updateDuplicates"
					v-if="responseSuccess">
	 			</copyscape-result>

	 			<textgear-result
	 				:grammar="textgear"
	 				v-if="isGrammarTrue">
	 			</textgear-result>
			
				<button type="button" class="btn btn-success" @click="generateRespintax">Respin</button>
				<button type="button" class="btn btn-warning" @click="processToCopyscape" ref="csButton">Copyscape</button>
				<button type="button" class="btn btn-danger" :disabled="isDisableSpinAndCs" @click="spinDuplicatesAndCopyscape" ref="spinAndCsButton">Spin Duplicates And Run Copyscape</button>
				<button type="button" class="btn btn-info" @click="processToTextGear" ref="tgButton">Check Grammar</button>
				&nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span>
				<span v-if="isError" style="color: red;">{{ error }}</span><br>
			</form>
		</div>
	</div>
</template>

<script>
	import CopyscapeResult from './CopyscapeResult.vue';
	import TextgearResult from './TextgearResult.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import { ArticleMixin } from './../../mixins/ArticleMixin.js';

	export default {
		props: ['token', 'spintaxResult', 'spin', 'type', 'article'],
		components: { CopyscapeResult, TextgearResult },
		mixins: [ CrudMixin, ArticleMixin ],
		mounted() {
			this.smArticle = this.article;

			this.summernoteSetup();
			this.scrollTopAfterSpin();
		},
		methods: {
			summernoteSetup() {
				// init summernote
				$('div#editor').summernote();

				// highlight summernote paragraph
				$('div.note-editable').find('p').text(this.article);
			},

			scrollTopAfterSpin() {
				// scroll window to div.Word__result
				$('html, body').animate({
					// scrollTop: $('div.Word__result').offset().top 
					scrollTop: 260
				}, 'slow', 'swing');
			}
		}
	}
</script>

<style scoped>
	h2 { text-align: center; }	
</style>