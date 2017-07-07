<template>
	<div class="form-group">
		<form method="POST">
			<input type="hidden" name="_token" :value="token">

			<textarea class="form-control" rows="12">{{ newParagraph }}</textarea>
			<br>

			<copyscape-result
				:copy="copyscape"
				v-if="responseSuccess">
 			</copyscape-result>

			<button type="button" class="btn btn-success" @click="generateRespintax(index)">Respin</button>
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
		props: [ 'token', 'paragraph', 'index', 'spin', 'type' ],
		components: { CopyscapeResult },
		mixins: [ CrudMixin, ArticleMixin ],
		data() {
			return {
				newParagraph: '',
			}
		},
		mounted() {
			this.newParagraph = this.paragraph;
		}
	}
</script>