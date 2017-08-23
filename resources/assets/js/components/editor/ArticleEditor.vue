<template>
	<div class="ArticleEditor">
		<h2 class="text-center">{{ article.doc_title }}</h2><hr>

		<div class="Spintax__result" v-if="peditoraccess">
			<h3>Spintax Result</h3>

			<div class="Peditor" v-if="! pEditorAccess">
				<p v-if="article.isEditorUpdateSC === 0">{{ article.spintax }}</p>
				<p v-else>{{ article.spintax_copy }}</p>
				<br>

				<button type="button" class="btn btn-default power-editor" @click="onPowerEditor" ref="pEditorBtn">Power Editor</button>
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
				<!-- Copyscape -->
				<div class="first-button">
					<div class="action" v-if="! csBusinessRuleShow">
						<button type="button" class="btn btn-warning" @click="processToCopyscape" ref="csButton">Copyscape</button>
					</div>
					<div class="business-rule" v-else>
						<button class="btn btn-business-rule" disabled>Copyscape check hits business rule</button>
					</div>
				</div>

				<!-- Respin -->
				<div class="first-button">
					<div class="action" v-if="! respinBusinessRuleShow">
			   			<button type="button" class="btn btn-info" @click="respinArticle" ref="respinBtn">Respin Article</button>
					</div>
					<div class="business-rule" v-else>
						<button class="btn btn-business-rule" disabled>Respin hits business rule</button>
					</div>
				</div>

				<!-- Dismiss -->
		        <button type="button" class="btn btn-danger" @click="dissmissArticle">Dismiss</button>

		        <!-- Misc -->
		        &nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span>
				<span v-if="isError" style="color: red;">{{ error }}</span><br>

                <!-- tmp -->
                <button type="button" class="btn btn-default" id="tmpSummernote">Temp</button>
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
				spin: {},
				pEditorAccess: false,
				csCounter: 0,
                respinCounter: 0,
				csBusinessRuleShow: false,
				respinBusinessRuleShow: false,
                charHighlighted: '',
			}
		},
		watch: {
			spin(data) {
                this.csBusinessRuleShow = data.isCsCheckHitMax === 1 ? true : false;
				this.respinBusinessRuleShow = data.isRespinHitMax === 1 ? true : false;
			}
		},
		mounted() {
			this.spin = this.article;
            this.initSummernote();
		},
		methods: {
            initSummernote() {
                let vm = this;
                let div = $('div#editor');

                // Setup summernote
                div.summernote({
                    callbacks: {
                        onInit() {
                            // Insert text
                            $('div.note-editable').find('p').html(vm.article.spin);

                            $('button#tmpSummernote').on('click', function(e) {
                                let range = div.summernote('createRange');

                                console.log(range.toString());

                                // console.log('select: ', e);
                            });
                        }
                    }
                });
            },

			updateArticle() {
				const data = {
					id: this.article.id,
					article: $('div.note-editable').text()
				};

                this.$refs.saveChangeBtn.disabled = true;

				axios.patch('/editor/updateArticle', data).then(response => {
					let data = response.data;

                    this.$refs.saveChangeBtn.disabled = false;

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
			},

			respinArticle() {
				this.isLoading = true;
				this.isError = false;
				this.$refs.respinBtn.disabled = true;

				// vars
				this.spin['article'] = $('div.note-editable').html();
				this.spin['type'] = 'edit-article';

                // check if type is 'edit-article'
                if (this.type === 'edit-article') {
                    this.respinCounter++;   // increment respinCounter
                }

				let editor = $('div.note-editable');
				editor.slideUp(); // hide editor

				axios.post('/words/respinArticle', this.spin).then(response => {
					let data = response.data;
					editor.slideDown(); // show editor
					editor.html(data);

					this.isLoading = false;
					this.$refs.respinBtn.disabled = false;

					// check if api response is fail
					if (data.status === 'Failure') {
						this.error = data.error;
						this.isError = true;
					}

                    // check if counter = 5
                    if (this.type == 'edit-article' && this.respinCounter == 5) {
                        this.updateRespinCheckHitMax();
                    }
				});
			},

			updateCsCheckHitMax() {
				const data = { word_id: this.article.id };

				axios.patch('/words/updateCsCheckHitMax', data).then(response => {
					if (response.data) {
						this.csBusinessRuleShow = true;
					}
				});
			},

            updateRespinCheckHitMax() {
                const data = { word_id: this.article.id };

                axios.patch('/words/updateRespinCheckHitMax', data).then(response => {
                    if (response.data) {
                        this.respinBusinessRuleShow = true;
                    }
                });
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

	div.first-button {
	    float: left;
	    margin-right: 0.2em;
	}

	/*=============== Gradient button ===============*/
	.btn-business-rule {
		background-color: hsl(0, 0%, 13%) !important;
		background-repeat: repeat-x;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#bababa", endColorstr="#212121");
		background-image: -khtml-gradient(linear, left top, left bottom, from(#bababa), to(#212121));
		background-image: -moz-linear-gradient(top, #bababa, #212121);
		background-image: -ms-linear-gradient(top, #bababa, #212121);
		background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #bababa), color-stop(100%, #212121));
		background-image: -webkit-linear-gradient(top, #bababa, #212121);
		background-image: -o-linear-gradient(top, #bababa, #212121);
		background-image: linear-gradient(#bababa, #212121);
		border: 1px solid #7d7d7d;
		color: #fff !important;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.99);
		-webkit-font-smoothing: antialiased;
	}
</style>