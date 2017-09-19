<template>
	<div class="Word">
		<h1>Create Article</h1><hr>

		<form method="POST">
			<input type="hidden" name="_token" :value="token">

			<div class="form-group">
				<label for="dom_name">Domain Name</label> &nbsp; <span style="color: red;" v-if="isDomainNotSet">This domain not set yet.</span>
				<!-- <input type="text" class="form-control" list="domains" v-model="spin.domain" @blur="domainChange">
				<datalist id="domains">
					<option v-for="domain in domains" :value="domain.domain" :data-domain-id="domain.id"></option>
				</datalist> -->

                <select class="form-control" v-model="spin.domain_id" @change="domainChange">
                    <option value="select">Select a domain</option>
                    <option v-for="domain in domains" :value="domain.id">{{ domain.domain }}</option>
                </select>
			</div>

			<div class="form-group">
				<label for="articleType">Article Type</label>
				<select class="form-control" v-model="spin.article_type_id" v-if="isArticleTypesLoaded">
					<option value="select">Please select an article type</option>
					<option v-for="type in articleTypes" :value="type.id">{{ type.article_type }}</option>
				</select>
			</div>

            <div class="form-group">
                <label for="group_name">Group Name</label>
                <input type="text" class="form-control" disabled="disabled" v-model="spin.group_name">
            </div>

			<div class="form-group">
				<label for="doc_title">Document Title</label>
				<input type="text" class="form-control" v-model="spin.doc_title">
			</div>

			<div class="form-group">
				<label for="keyword">Key Word/Phrase field</label>
				<input type="text" class="form-control" maxlength="255" v-model="spin.keyword">
			</div>

			<div class="row">
				<!-- LSI Terms -->
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<label for="lsi_terms">LSI Terms</label>
					<textarea class="form-control" rows="8" v-model="spin.lsi_terms"></textarea>
				</div>

				<!-- Synonyms -->
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<label for="synonyms">Synonyms</label>
					<textarea class="form-control" rows="8" v-model="spin.synonym"></textarea>
				</div>
			</div><br>

			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<label for="protected">Protected Terms</label>
					<textarea class="form-control" rows="8" v-model="spin.protected"></textarea>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<label for="domain_protected">Domain Protected Terms</label>
					<textarea class="form-control" rows="8" v-model="spin.domain_protected"></textarea>
				</div>
			</div><br>

			<!-- <textarea class="form-control" rows="8" :maxlength="wordsMax" v-model="words" @keyup="wordCount"></textarea> -->
			<label for="article">Original Article</label>
			<textarea class="form-control" id="article" rows="40" v-once v-model="spin.article" @keyup="wordCount"></textarea>
			<br>

			<!-- Erorr component -->
			<error
				:type="errorType"
				:list="errors"
				v-if="isValidationFail">
			</error>
			<br>

			<div class="form-group">
				<span>Words count: <b>{{ count }}</b></span>
			</div>
			<br>

            <button type="submit" class="btn btn-primary" ref="spinButton" @click.prevent="saveArticle">Save Article</button>
            <!-- tmp -->
			<!-- <button type="submit" class="btn btn-primary" ref="spinButton" @click.prevent="saveAndProcessNow">Save & Process Now</button> -->
			&nbsp;&nbsp;&nbsp;
			<span v-if="isLoading">LOADING....</span><br>
		</form>
	</div>
</template>

<script>
	import UniqueHandwritten from './UniqueHandwritten.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';
    import { ArticleActionMixin } from './../../mixins/ArticleActionMixin.js';
	import { CreateArticleMixin } from './../../mixins/CreateArticleMixin.js';

	export default {
		components: { UniqueHandwritten },
		mixins: [
            CrudMixin,
            ArticleActionMixin,
            CreateArticleMixin
        ]
	}
</script>

<style scoped>
    ul.alert.alert-danger { position: inherit; }
    .Word select { text-transform: uppercase; }
</style>