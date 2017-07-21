<template>
	<div class="Word">
		<div class="form-group">
			<h1>Article</h1><hr>

			<label for="articleType">Article Type</label>
			<select class="form-control" v-model="articleType" v-if="isArticleTypesLoaded">
				<option value="select">Please select an article type</option>
				<option v-for="type in articleTypes" :value="type.id">{{ type.article_type }}</option>
			</select>
		</div>

		<!-- handwritten -->
		<div class="Handwritten" v-if="articleType === 1">
			<unique-handwritten></unique-handwritten>
		</div>

		<!-- curated -->
		<div class="Curated" v-else-if="articleType === 2">
			<div class="Word__result" v-if="isSuccess">
				<full-article
					:token="token"
					:spintaxResult="spintaxResult"
					:spin="spin"
					:article="article"
					:type="spintaxType = 'article'">
				</full-article><br>
			</div>

			<form method="POST" @submit.prevent="spinTax">
				<input type="hidden" name="_token" :value="token">

				<!-- <textarea class="form-control" rows="8" :maxlength="wordsMax" v-model="words" @keyup="wordCount"></textarea> -->
				<label for="article">Original Article</label>
				<textarea class="form-control" rows="40" v-once v-model="spin.article" @keyup="wordCount"></textarea>
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

				<button type="submit" class="btn btn-primary" ref="spinButton">Spin Now</button>
				&nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span><br>
			</form>
		</div>
	</div>
</template>

<script>
	import UniqueHandwritten from './UniqueHandwritten.vue';
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import { ArticleActionMixin } from './../../mixins/ArticleActionMixin.js';

	export default {
		components: { UniqueHandwritten },
		mixins: [ CrudMixin, ArticleActionMixin ]
	}
</script>