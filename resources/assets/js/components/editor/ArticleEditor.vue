<template>
	<div class="ArticleEditor">
		<h2 class="text-center">{{ article.doc_title }}</h2><hr>

		<div class="Spintax__result">
			<div class="Peditor" v-if="! pEditorAccess">
				<h3>Spintax Result</h3>
				<p>{{ article.spintax }}</p>

				<button type="button" class="btn btn-default power-editor" v-if="peditoraccess" @click="onPowerEditor" ref="pEditorBtn">Power Editor</button>
			</div>

			<!-- Power Editor -->
			<power-editor
				:article="article"
				v-if="pEditorAccess"
				@isPowerEditorDismiss="pEditorAccess = false">
			</power-editor>
		</div>

		<div class="Process__article">
			<h3>Processed Article</h3>
			<div class="Editor">
				<div id="editor"></div>
				<button type="button" class="btn btn-success right-side" @click="updateArticle" ref="saveChangeBtn">Save Article Changes</button>
			</div>

			<copyscape-result
				:copy="copyscape"
				@updateduplicates="updateDuplicates"
				v-if="responseSuccess">
 			</copyscape-result>

			<div class="Actions">
				<button type="button" class="btn btn-warning" @click="processToCopyscape" ref="csButton">Copyscape</button>
			    <button type="button" class="btn btn-info" @click="generateRespintax">Respin Article</button>
		        <button type="button" class="btn btn-danger" @click="dissmissArticle">Dismiss</button>
		        &nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span>
				<span v-if="isError" style="color: red;">{{ error }}</span><br>
			</div>
		</div>
	</div>
</template>

<script>
	import PowerEditor from './PowerEditor.vue';
	import CopyscapeResult from './../words/CopyscapeResult.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import { ArticleMixin } from './../../mixins/ArticleMixin.js';

	export default {
		props: ['article', 'peditoraccess'],
		components: { PowerEditor, CopyscapeResult },
		mixins: [ CrudMixin, ArticleMixin ],
		data() {
			return {
				type: 'edit-article',
				spin: {
					paragraph: '',
					type: 'edit-article'
				},
				pEditorAccess: false
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
			},

			onPowerEditor() {
				this.pEditorAccess = true;
			},

			dissmissSpintaxArticle() {
				this.pEditorAccess = false;
			}
		}
	}
</script>

<style scoped>
	.ArticleEditor { margin-bottom: 3em; }
	h3 { text-align: center; }
	p { white-space: pre-wrap; }

	.Editor { position: relative; }
	button.right-side {
		position: absolute;
		right: 0;
	}

	button.power-editor {
		background: #D13DD1;
		color: #fff;
		border: #8c3f8c;
	}
	button.power-editor:hover { background: #BC2EBC; }
	.Copyscape { margin-top: 5em; }
</style>