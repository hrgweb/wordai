<template>
	<div class="Copyscape">
		<div class="results_title">
			<b>{{ copy.count }}  result</b> 
			found for the text you pasted&nbsp; ({{ copy.querywords }} words).
		</div>

		<div class="Copyscape__result" v-for="copy in copy.result">
			<a :href="copy.url" target="_tab">{{ copy.title }}</a>
			<p>{{ copy.textsnippet }}</p>
			<em>{{ copy.url }}</em>
		</div>
	</div>
</template>

<script>
	export default {
		props: [ 'copy' ],
		mounted() {
			this.articleDuplicates();
		},
		methods: {
			articleDuplicates() {
				let article = $('div.note-editable').find('p').html();
				let duplicates = article.match(/\<mark\>.+?\<\/mark\>/g);

				this.$emit('updateduplicates', duplicates);
			}
		}
	}
</script>

<style scoped>
	.results_title {
	    text-align: left;
	    background-color: #e5e5ff;
	    padding: 6px 15px;
	    margin-bottom: 20px;
	    margin-top: 0;
	    font-size: 13px;
	    border: 1px solid #CECEF0;
	}
	
	.Copyscape__result {
	    margin-bottom: 2em;
	    background: #e5e8ea;
	    padding: .5em 1em;
	}
</style>