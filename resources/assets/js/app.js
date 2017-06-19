require('./bootstrap');

window.Vue = require('vue');

// 
import Admin from './components/admin/Admin.vue';
import WordApi from './components/words/WordApi.vue';
import GenerateArticle from './components/words/GenerateArticle.vue';

const app = new Vue({
	name: 'WordAI',
    el: '#app',
    components: {
    	Admin,
    	WordApi,
    	GenerateArticle
    }
});
