<template>
	<div class="Word">
		<div class="Word__result" v-show="isSuccess">
			<!-- Full Article -->
			<full-article
				:token="token"
				:spintaxResult="spintaxResult"
				:spin="spin"
				:article="article"
				:type="spintaxType = 'article'"
				v-if="isSuccess">
			</full-article><br>
		</div>

		<h1>Article</h1><hr>

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
</template>

<script>
	import { CrudMixin } from './../../mixins/CrudMixin.js';
	import { ArticleActionMixin } from './../../mixins/ArticleActionMixin.js';

	export default {
		mixins: [ CrudMixin, ArticleActionMixin ]
	}
</script>