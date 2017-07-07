<template>
	<div class="Article">
		<h2>Article</h2>

		<form method="POST" role="form">
			<input type="hidden" name="_token" :value="token">
		
			<textarea class="form-control" rows="40">{{ article }}</textarea>
			<br>

			<copyscape-result
				:copy="copyscape"
				v-if="responseSuccess">
 			</copyscape-result>
		
			<button type="submit" class="btn btn-success">Respin</button>
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
		props: ['token', 'spin', 'type', 'article'],
		components: { CopyscapeResult },
		mixins: [ CrudMixin, ArticleMixin ]
	}
</script>