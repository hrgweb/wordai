require('./bootstrap');

/*=============== Class ===============*/
import Form from './class/Form.js';

/*=============== Global vars ===============*/
import { ReportingBus } from './eventbus/ReportingBus.js';
import { ArticleBus } from './eventbus/ArticleBus.js';
import { DomainBus } from './eventbus/DomainBus.js';


window.$ = $;
window.Vue = require('vue');
window.Form = Form;
window.moment = require('moment');
window.Noty = require('noty');
window.ReportingBus = ReportingBus;
window.ArticleBus = ArticleBus;
window.DomainBus = DomainBus;



/*=============== Components ===============*/

import Dashboard from './components/admin/Dashboard.vue';
import Admin from './components/admin/Admin.vue';
import ArticleMain from './components/admin/ArticleMain.vue';
import Domain from './components/admin/Domain.vue';
import CreateGroup from './components/admin/CreateGroup.vue';
import User from './components/admin/User.vue';
import ProtectedTerm from './components/admin/ProtectedTerm.vue';
import DomainGroup from './components/admin/DomainGroup.vue';
import DomainDetail from './components/admin/DomainDetail.vue';
import Copyscape from './components/admin/Copyscape.vue';
import EditArticle from './components/admin/EditArticle.vue';
import SearchArticle from './components/admin/SearchArticle.vue';
import SearchBy from './components/admin/SearchBy.vue';
import ArticleReport from './components/admin/ArticleReport.vue';
import ArticleEdited from './components/admin/ArticleEdited.vue';
import ArticleToEdit from './components/admin/ArticleToEdit.vue';
import WordApi from './components/words/WordApi.vue';
import WordApi2 from './components/words/WordApi2.vue';
import CurationEdit from './components/words/CurationEdit.vue';
import PasteArticle from './components/words/PasteArticle.vue';
import CreateArticle from './components/words/CreateArticle.vue';
import CopyscapeApiResult from './components/words/CopyscapeApiResult.vue';
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
        ArticleMain,
    	Domain,
        CreateGroup,
    	User,
    	ProtectedTerm,
        DomainGroup,
    	DomainDetail,
    	Copyscape,
        EditArticle,
        SearchArticle,
        SearchBy,
        ArticleReport,
        ArticleEdited,
        ArticleToEdit,
    	WordApi,
    	WordApi2,
    	CurationEdit,
    	PasteArticle,
    	CreateArticle,
    	CopyscapeApiResult,
    	GenerateArticle,
    	UserArticle,
    	Editor,
    	SuperEditor
    }
});
