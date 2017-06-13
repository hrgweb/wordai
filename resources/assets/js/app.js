require('./bootstrap');

window.Vue = require('vue');

// 
import WordApi from './components/words/WordApi.vue';

const app = new Vue({
	name: 'WordAI',
    el: '#app',
    components: {
    	WordApi
    }
});
