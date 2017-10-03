<template>
	<div class="ArticleEditor">
		<div class="Process__article">
			<h3>
				Edit Article: &nbsp;
				<span style="color: #C9302C;">{{ data.doc_title }}</span>
			</h3><br>
			<div id="editor"></div>

			<div class="Actions">
			    <button type="button" class="btn btn-info" @click="updateArticle">Update Article</button>
		        <button type="button" class="btn btn-danger" @click="dissmissArticle">Dismiss</button>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['data', 'index'],
		data() {
			return {
			}
		},
		mounted() {
			$('div#editor').summernote('editor.insertText', this.data.article);
		},
		methods: {
			updateArticle() {
				const data = {
					word_id: this.data.id,
					article: $('div.note-editable').text()
				};

				axios.patch('/user/updateArticle', data).then(response => {
					if (response.data) {
						ArticleBus.$emit('isUpdated', {
							article: data.article,
                            index: this.index
						});
					}
				});
			},

			dissmissArticle() {
				this.$emit('cancelEdit');
			}
		}
	}
</script>