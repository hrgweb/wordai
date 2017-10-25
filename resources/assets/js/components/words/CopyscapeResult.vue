<template>
	<div class="Copyscape">
        <div class="row">
            <div class="col-md-6">
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

            <div class="cs-duplicates col-md-6">
                <h4>
                    Copyscape Duplicates&nbsp;
                    <span class="badge">{{ duplicates.length }}</span>
                </h4><hr>

                <!-- has duplicates -->
                <ul class="duplicates" v-if="duplicates.length > 0">
                    <li v-for="duplicate in duplicates">{{ duplicate }}</li>
                </ul>
                <!-- no duplicates -->
                <ul class="duplicates" v-else>
                    <li><em>No duplicaets found</em></li>
                </ul>
            </div>
        </div>

	</div>
</template>

<script>
	export default {
		props: [ 'copy', 'duplicates' ],
		mounted() {
			this.articleDuplicates();
		},
		methods: {
			articleDuplicates() {
				let article = $('div.Process__article').find('div.note-editable').html();
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

    .cs-duplicates {
        background: #ff5b5b;
        color: #fff;
        margin-bottom: 5em;
    }

    .cs-duplicates h4 {
        padding-top: 1em;
        padding-left: 0.3em;
    }

    ul.duplicates { padding-bottom: 1em; }
    ul.duplicates li {
        font-size: 1.1em;
        margin-bottom: 0.5em;
    }
</style>