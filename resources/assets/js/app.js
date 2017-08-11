require('./bootstrap');

window.$ = $;
window.Vue = require('vue');
window.moment = require('moment');
window.Noty = require('noty');

/*=============== Components ===============*/  

import Admin from './components/admin/Admin.vue';
import PendingUser from './components/admin/PendingUser.vue';
import Domain from './components/admin/Domain.vue';
import User from './components/admin/User.vue';
import ProtectedTerm from './components/admin/ProtectedTerm.vue';
import DomainDetail from './components/admin/DomainDetail.vue';
import WordApi from './components/words/WordApi.vue';
import WordApi2 from './components/words/WordApi2.vue';
import CurationEdit from './components/words/CurationEdit.vue';
import PasteArticle from './components/words/PasteArticle.vue';
import CreateArticle from './components/words/CreateArticle.vue';
import CopyscapeApiResult from './components/words/CopyscapeApiResult.vue';
// import CurlPage from './components/words/CurlPage.vue';
import GenerateArticle from './components/words/GenerateArticle.vue';
import UserArticle from './components/words/UserArticle.vue';
import Editor from './components/editor/Editor.vue';

const app = new Vue({
	name: 'WordAI',
    el: '#app',
    components: {
    	Admin,
    	PendingUser,
    	Domain,
    	User,
    	ProtectedTerm,
    	DomainDetail,
    	WordApi,
    	WordApi2,
    	CurationEdit,
    	PasteArticle,
    	CreateArticle,
    	CopyscapeApiResult,
    	// CurlPage,
    	GenerateArticle,
    	UserArticle,
    	Editor
    }
});
