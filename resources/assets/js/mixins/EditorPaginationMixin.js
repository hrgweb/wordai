import Editor from './../class/Editor.js';

export const EditorPaginationMixin = {
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
                this.articles = this.editor.mapResultOfArticles(response.data.data);

                ReportingBus.$emit('isLoadedListEditedArticles', this.articles);
            });
        }
    }
};