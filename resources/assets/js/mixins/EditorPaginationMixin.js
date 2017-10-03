import Paginate from 'vuejs-paginate'
import Editor from './../class/Editor.js';

export const EditorPaginationMixin = {
    components: { Paginate },
    data() {
        return {
            articlesCount: 0,
            articles: [],
            editor: new Editor(),
            pageCount: 0,
            pageNum: 1,
            urlPath: '',
            report: ReportingBus,
        };
    },

    computed: {
        pagePath() {
            return this.urlPath + '?page=' + this.pageNum;
        }
    },

    watch: {
        articles(data) {
            this.articlesCount = data.length;
        }
    },

    methods: {
        paginatePage(pageNum) {
            this.pageNum = pageNum;

            axios.get(this.pagePath).then(response => {
                if (this.tableType === 'writer-article') {
                    this.articles = response.data.data;
                } else {
                    this.articles = this.editor.mapResultOfArticles(response.data.data);
                }

                // check type of table
                if (this.tableType === 'article-to-edit') {
                    ReportingBus.$emit('isLoadedListToEdit', this.articles);
                } else {
                    ReportingBus.$emit('isLoadedListEditedArticles', this.articles);
                }
            });
        }
    }
};