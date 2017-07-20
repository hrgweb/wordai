<template>
	<div class="Article">
		<div class="Process__article">
			<h2>Copyscape - GUI</h2>
			<form method="POST" role="form">
				<input type="hidden" name="_token" :value="token">
			
				<div id="editor">{{ smArticle }}</div>
				<br>

				<copyscape-result
					:copy="copyscape"
					v-if="responseSuccess">
	 			</copyscape-result>

	 			<textgear-result
	 				:grammar="textgear"
	 				v-if="isGrammarTrue">
	 			</textgear-result>
			
				<!-- <button type="button" class="btn btn-success" @click="generateRespintax">Respin</button> -->
				<button type="button" class="btn btn-warning" @click="processCopyscapeApi" ref="csButton">Copyscape</button>
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

			// init summernote
			$('div#editor').summernote();

			// highlight summernote paragraph
			$('div.note-editable').find('p').text(this.article);
		},
		methods: {
			processCopyscapeApi() {
				const data = { article: $('div.note-editable').text() };

				// process copyscape api
				this.copyScapeSetup('/words/processCopyscapeApi', data);
			}
		}
	}
</script>

<style scoped>
	h2 { text-align: center; }	
	.Process__article { background: #fff; }
</style>