<template>
	<div class="ArticleEditor">
		<h2 class="text-center">{{ article.doc_title }}</h2><hr>

		<div class="Spintax__result" v-if="peditoraccess">
			<h3>Spintax Result</h3><br>

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
				@isPowerEditorDismiss="dismissPowerEditor">
			</power-editor>
		</div>

		<div class="Process__article">
			<h3>Processed Article</h3><br>
			<div class="Editor">
				<div id="editor"></div>
				<button type="button" class="btn btn-success right-side" @click="updateArticle" ref="saveChangeBtn">Save Article Changes</button>
			</div>

            <!-- textgear -->
            <textgear-result
                :grammar="textgear"
                v-if="isGrammarTrue"
                @isClose="closeTextGear">
            </textgear-result>

            <!-- copyscape -->
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

                <!-- Textgear -->
                <button type="button" class="btn btn-info textgear" @click="processToTextGear" ref="tgButton">Check Grammar</button>

				<!-- Dismiss -->
		        <button type="button" class="btn btn-danger" id="close" @click="dissmissArticle">Back to Articles</button>

		        <!-- Misc -->
		        &nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span>
				<span v-if="isError" style="color: red;">{{ error }}</span><br>

                <!-- Stopwatch -->
                <div class="timer-overlay">
                    <span>Time Spent Editing Article</span>
                    <div class="stopwatch"></div>
                </div>
			</div>
		</div>
	</div>
</template>

<script>
	import PowerEditor from './PowerEditor.vue';
	import CopyscapeResult from './../words/CopyscapeResult.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import { ArticleMixin } from './../../mixins/ArticleMixin.js';
    import Stopwatch from './../../class/Stopwatch.js';
    import TextgearResult from './../words/TextgearResult.vue';

	export default {
		props: ['article', 'peditoraccess'],
		components: { PowerEditor, CopyscapeResult, TextgearResult },
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
                clock: {},
                times: [0, 0, 0]
			}
		},
		watch: {
			spin(data) {
                this.csBusinessRuleShow = data.isCsCheckHitMax === 1 ? true : false;
				this.respinBusinessRuleShow = data.isRespinHitMax === 1 ? true : false;
			},

            pEditorAccess() {
                Vue.nextTick(() => this.updateFirstSummernote($('div.Peditor'), this.article));
            }
		},
		mounted() {
			this.spin = this.article;
            this.initSummernote();
            this.initStopwatch();
		},
		methods: {
            updateFirstSummernote(div, article) {
                let p = div.find('p');

                // check if ther is paragraph && there is spintax_copy and not = to null
                if (p.length > 0 && article.isEditorUpdateSC === 1) {
                    Vue.nextTick(() => p.text(article.spintax_copy));
                } else {
                    Vue.nextTick(() => p.text(article.spintax));
                }
            },

            changeCurlyColor() {
                let spintax = $('div.Peditor').find('p').html();
                let result = spintax.replace(/[{}|]/g, function(char, offset, string) {
                    let span = '';

                    if (char.match(/[{}]/)) {
                        span = '<span class="curly">' + char + '</span>'
                    } else {
                        span = '<span class="pipe">' + char + '</span>'
                    }

                    return span;
                });

                $('div.Peditor').find('p').html(result);
            },

            initSummernote() {
                let vm = this;
                let article = vm.article;
                let div = $('div#editor');

                // Setup summernote
                div.summernote({
                    callbacks: {
                        onInit() {
                            // 1st summernote - format paragraph
                            vm.updateFirstSummernote($('div.Peditor'), article);

                            // change curly color
                            Vue.nextTick(() => vm.changeCurlyColor());

                            // 2nd summernote - insert text
                            $('div.note-editable').find('p').html(article.spin);
                            // vm.updateFirstSummernote($('div.note-editable'), article);
                        }
                    }
                });
            },

            initStopwatch() {
                let article = this.article;

                this.times = [
                    article.hr_spent_editor_edit_article,
                    article.min_spent_editor_edit_article,
                    article.sec_spent_editor_edit_article
                ];

                this.clock = new Stopwatch(
                    this.times,
                    document.querySelector('.stopwatch'),
                    document.querySelector('.results')
                );
                this.clock.start();
            },

			updateArticle() {
				const data = {
					id: this.article.id,
					article: $('div.note-editable').text(),
                    times: this.times
				};

                this.$refs.saveChangeBtn.disabled = true;

				axios.patch('/editor/updateArticle', data).then(response => {
					let data = response.data;

                    this.$refs.saveChangeBtn.disabled = false;

					if (data.isSuccess) {
						this.$emit('isUpdated', {
                            article: data.result,
                            times: data.times
                        });
					}
				});
			},

			dissmissArticle() {
                this.editorSpentTimeOnEditingArticle();
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
				// this.spin['article'] = $('div.note-editable').html();
				this.spin['type'] = 'edit-article';

                // check if type is 'edit-article'
                if (this.type === 'edit-article') {
                    this.respinCounter++;   // increment respinCounter
                }

				let editor = $('div.note-editable');
				editor.slideUp(); // hide editor

                // params
                const params = {
                    word_id: this.article.id,
                    article: this.article.spintax_copy
                };

				axios.post('/words/respinArticle', params).then(response => {
					let data = response.data;
					editor.slideDown(); // show editor
					editor.html(data);

					this.isLoading = false;
					this.$refs.respinBtn.disabled = false;

                    // update article obj in vue data
                    ArticleBus.$emit('isRespinArticle', { spin: data });

					// check if api response is fail
					/*if (data.status === 'Failure') {
						this.error = data.error;
						this.isError = true;
					}*/

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
            },

            editorSpentTimeOnEditingArticle(emitType) {
                const data = {
                    word_id: this.article.id,
                    times: this.times
                };

                axios.patch('/editor/editorSpentTimeOnEditingArticle', data).then(response => {
                    let data = response.data;

                    if (data) {
                        this.$emit('isDismiss', data);
                    }
                });
            },

            dismissPowerEditor() {
                this.pEditorAccess = false;
            },

            closeTextGear() {
                this.isGrammarTrue = false;
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
	.Copyscape, .errorlist { margin-top: 5em; }

    button.textgear { background-color: #af863f !important; }
    button.textgear:hover {
        background-color: #9D7734 !important;
        border: 1px solid #936B25 !important;
    }

	div.first-button {
	    float: left;
	    margin-right: 0.2em;
	}

    .stopwatch {
        font-size: 2em;
        text-align: center;
    }

    .timer-overlay {
        position: fixed;
        background: #080808;
        color: #fff;
        left: 0;
        bottom: 33em;
        padding-top: 1em;
        padding-left: 1.2em;
        padding-right: .5em;
        /* border-top-left-radius: .5em; */
        /* border-bottom-left-radius: .5em; */
        /* border: 5px solid #ecdfdf; */
        /* border-left-width: 0; */
        z-index: 999999;
    }
    .timer-overlay span { font-size: 14px; }

    #close {
        position: fixed;
        top: 4.6em;
        right: 0;
        padding: 1em 4em 1em 1em;
        font-weight: bold;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
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