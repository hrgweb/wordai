<template>
	<div class="Article">
		<h2>Spintax Result</h2>
		<p style="white-space: pre-wrap;">{{ spintaxResult }}</p><br>

		<h2>Processed Article</h2>
		<form method="POST" role="form">
			<input type="hidden" name="_token" :value="token">
		
			<div id="editor">{{ article }}</div>
			<!-- <textarea class="form-control" rows="40">{{ article }}</textarea> -->
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
			$('div#editor').summernote();
		}
	}
</script>

<style scoped>
	h2 { text-align: center; }
</style>