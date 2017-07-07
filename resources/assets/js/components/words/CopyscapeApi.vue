<template>
	<div class="CopyscapeApi">
		<h2>CopyScape API</h2>

		<form method="POST" role="form">
			<input type="hidden" name="_token" :value="token">
		
			<textarea class="form-control" rows="15" v-model="article"></textarea>
			<br>

			<copyscape-result
				:copy="copyscape"
				v-if="responseSuccess">
 			</copyscape-result>
		
			<button type="button" class="btn btn-warning" @click="processToCopyscape">Copyscape</button>
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
		props: ['token'],
		components: { CopyscapeResult },
		mixins: [ CrudMixin, ArticleMixin ],
		data() {
			return {
				article: ''
			}
		},
		methods: {
			processToCopyscape() {
				this.isLoading = true;
				this.isError = false;

				// call to copyscape api
				this.copyScapeSetup('/words/processCopyscapeApi', { article: this.article });
			}
		}
	}
</script>