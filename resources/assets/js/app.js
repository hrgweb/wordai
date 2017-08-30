require('./bootstrap');

/*=============== Global vars ===============*/
import { ReportingBus } from './eventbus/ReportingBus.js';

window.$ = $;
window.Vue = require('vue');
window.moment = require('moment');
window.Noty = require('noty');
window.ReportingBus = ReportingBus;


/*=============== Components ===============*/

import Dashboard from './components/admin/Dashboard.vue';
import Admin from './components/admin/Admin.vue';
import Domain from './components/admin/Domain.vue';
import User from './components/admin/User.vue';
import ProtectedTerm from './components/admin/ProtectedTerm.vue';
import DomainDetail from './components/admin/DomainDetail.vue';
import Copyscape from './components/admin/Copyscape.vue';
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
import SuperEditor from './components/editor/SuperEditor.vue';

const app = new Vue({
	name: 'Connexions Content Suite',
    el: '#app',
    components: {
        Dashboard,
    	Admin,
    	Domain,
    	User,
    	ProtectedTerm,
    	DomainDetail,
    	Copyscape,
    	WordApi,
    	WordApi2,
    	CurationEdit,
    	PasteArticle,
    	CreateArticle,
    	CopyscapeApiResult,
    	// CurlPage,
    	GenerateArticle,
    	UserArticle,
    	Editor,
    	SuperEditor
    }
});
