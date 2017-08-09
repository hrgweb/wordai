<template>
	<div class="ArticleEditor">
		<!-- <div class="Spintax__result">
			<h3>Spintax Result</h3>
			<p>{{ article.spintax }}</p>
		</div> -->

		<div class="Process__article">
			<h3>Processed Article</h3>
			<div id="editor"></div>

			<copyscape-result
				:copy="copyscape"
				@updateduplicates="updateDuplicates"
				v-if="responseSuccess">
 			</copyscape-result>

			<div class="Actions">
				<button type="button" class="btn btn-warning" @click="processToCopyscape" ref="csButton">Copyscape</button>
			    <button type="button" class="btn btn-info" @click="updateArticle">Update Article</button>
		        <button type="button" class="btn btn-danger" @click="dissmissArticle">Dismiss</button>
		        &nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span>
				<span v-if="isError" style="color: red;">{{ error }}</span><br>
			</div>
		</div>
	</div>
</template>

<script>
	import CopyscapeResult from './../words/CopyscapeResult.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import { ArticleMixin } from './../../mixins/ArticleMixin.js';

	export default {
		props: ['article'],
		components: { CopyscapeResult },
		mixins: [ CrudMixin, ArticleMixin ],
		data() {
			return {
				type: 'edit-article',
				spin: {
					article: '',
					type: 'edit-article'
				}
			}
		},
		mounted() {
			$('div#editor').summernote('editor.insertText', this.article.spin);
		},
		methods: {
			updateArticle() {
				const data = { 
					id: this.article.id,
					article: $('div.note-editable').text() 
				};

				axios.patch('/editor/updateArticle', data).then(response => {
					let data = response.data;

					if (data.isSuccess) {
						this.$emit('isUpdated', { article: data.result });
					}
				});
			},

			dissmissArticle() {
				this.$emit('isDismiss');
			}
		}
	}
</script>

<style scoped>
	.ArticleEditor { margin-bottom: 3em; }
	h3 { text-align: center; }
	p { white-space: pre-wrap; }
</style>