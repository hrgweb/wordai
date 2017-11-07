import Vue from 'vue';
import Editor from './../class/Editor.js'
import Form from './../class/Form.js'

export const ReportingBus = new Vue({
    data() {
        return {
            articles: [],
            isEditorEditBtnDisable: false,
            reportingFilter: {
                groupBy: 'select',
                orderBy: 'date',
                sortBy: 'asc'
            },
            date: {
                fromMon: 0,
                toSun: 0,
                curMonth: 0,
                curYear: 0,
                monthSame: false
            },
            fromUtc: '',
            toUtc: '',
            isSameMonth: false,
            editedThisWeek: [],
            waitingToEdit: [],
            spunThisWeek: [],
            noOfArticlesThisWeek: 0,
            noOfArticlesEditedThisWeek: 0,
            noOfArticlesToEditThisWeek: 0,
            noOfArticlesSpunThisWeek: 0,
            creatorOfArticles: [],
            editor: new Editor(),
            form: new Form({
                from: '',
                to: '',
                input: ''
            }),
            searchByArticlesData: [],
            searchByArticlesCount: 0,
            searchBy: 'select',
            isLoading: false
        };
    },

    computed: {
        placeHolder() {
            return 'Seach for ' + this.searchBy;
        },

        headerCaption() {
            let text = '';

            if (this.searchBy === 'select') {
                text = 'Please select what to search';
            } else if (this.searchBy === 'range') {
                text = 'Search by range data';
            } else {
                text = 'Search by ' + this.searchBy + ': `' + this.form.input + '`';
            }

            return text;
        }
    },

    watch: {
        date() {
            this.articlesThisWeek();
        },

        articles(data) {
            // vars
            this.noOfArticlesThisWeek = data.length;

            // methods
            this.articlesEditedThisWeek();
            this.articlesWaitToEdit();
            this.articlesSpunThisWeek();
        },

        searchByArticlesData(data) {
            this.searchByArticlesCount = data.length;
        }
    },

    methods: {
        setDayFromMonToSun() {
            let curr = new Date; // get current date
            let mon = (curr.getDate() - curr.getDay()) + 1;
            let sun = mon + 6;

            // set data in vue
            this.fromUtc = new Date(curr.setDate(mon)).toUTCString();
            this.toUtc = new Date(curr.setDate(sun)).toUTCString();

            // get date from mon to sun
            mon = this.fromUtc.substr(5, 2);
            sun = this.toUtc.substr(5, 2);

            // get month
            let firstday = this.fromUtc.substr(8, 3);
            let lastday = this.toUtc.substr(8, 3);

            console.log(firstday, lastday)


            // check if month is = if not substract 1
            let month = 0;

            /*if (firstday.toLowerCase() === lastday.toLowerCase()) {
                this.isSameMonth = true;
                this.date = {
                    fromMon: mon,
                    toSun: sun,
                    curMonth: curr.getMonth() + 1,
                    curYear: curr.getFullYear()
                };
            } else {
                this.isSameMonth = false;
                this.date = {
                    fromMon: mon,
                    toSun: sun,
                    curMonthMon: (curr.getMonth() - 1) + 1,
                    curMonthSun: curr.getMonth() + 1,
                    curYear: curr.getFullYear()
                };
            }

            this.date['monthSame'] = this.isSameMonth;*/
        },

        last7days() {
            // set data in vue
            let date = new Date();
            this.toUtc = moment().format('L');
            this.fromUtc = moment().subtract(7, 'days').calendar();

            // get date from start days to last 7 days
            let dayStart = this.toUtc.substr(3, 2);
            let dayPrev = this.fromUtc.substr(3, 2);

            // get month
            let monthStart = this.fromUtc.substr(0, 2);
            let monthPrev = this.toUtc.substr(0, 2);

            // console.log(monthStart, monthPrev)

            // check if month is = if not substract 1
            if (parseInt(monthStart, 10) === parseInt(monthPrev, 10)) {
                this.isSameMonth = true;
                this.date = {
                    toSun: dayStart,
                    fromMon: dayPrev,
                    curMonth: date.getMonth() + 1,
                    curYear: date.getFullYear()
                };
            } else {
                this.isSameMonth = false;
                this.date = {
                    toSun: dayStart,
                    fromMon: dayPrev,
                    curMonthMon: (date.getMonth() - 1) + 1,
                    curMonthSun: date.getMonth() + 1,
                    curYear: date.getFullYear()
                };
            }

            this.date['monthSame'] = this.isSameMonth;
        },

        paramsForDate() {
            let date = this.date;

            if (this.isSameMonth === true) {
                return '?fromMon=' + date.fromMon + '&toSun=' + date.toSun + '&curMonth=' + date.curMonth + '&curYear=' + date.curYear + '&monthSame=' + date.monthSame;
            } else {
                return '?fromMon=' + date.fromMon + '&toSun=' + date.toSun + '&curMonthMon=' + date.curMonthMon + '&curMonthSun=' + date.curMonthSun + '&curYear=' + date.curYear + '&monthSame=' + date.monthSame;
            }
        },

        articlesThisWeek() {
            let params = this.paramsForDate();

            axios.get('/admin/articlesThisWeek' + params).then(response => this.articles = this.editor.mapResultOfArticles(response.data));
        },

        articlesEditedThisWeek() {
            let result = this.articles.filter(item => {
                return (item.isEditorEdit === 1 && item.isProcess === 1);
            });

            this.editedThisWeek = result;
            this.noOfArticlesEditedThisWeek = result.length;
        },

        articlesWaitToEdit() {
            let result = this.articles.filter(item => {
                return (item.isEditorEdit === 0 && item.isProcess === 1);
            });

            this.waitingToEdit = result;
            this.noOfArticlesToEditThisWeek = result.length;
        },

        articlesSpunThisWeek() {
            let result = this.articles.filter(item => {
                return item.isProcess === 1;
            });

            this.spunThisWeek = result;
            this.noOfArticlesSpunThisWeek = result.length;
        },

        filterGroupByChanged() {
            let params = this.paramsForDate();

            switch (this.reportingFilter.groupBy) {
                case 'user':
                    axios.get('/admin/articlesCreator' + params).then(response => this.creatorOfArticles = response.data);
                    break;
                case 'domain':
                    axios.get('/admin/articlesDomain' + params).then(response => this.creatorOfArticles = response.data);
                    break;
            }
        },

        /*=============== Search By ===============*/

        initSetupDate() {
            let dateObj = new Date();
            let month = dateObj.getMonth() + 1; //months from 1-12
            month = parseInt(month, 10) > 9 ? month : '0' + month;
            let day = dateObj.getDate();
            day = parseInt(day, 10) > 9 ? day : '0' + day;
            let year = dateObj.getFullYear();

            this.form['from'] = year + "-" + month + "-" + day;
            this.form['to'] = year + "-" + month + "-" + day;
        },

        searchArticlesByRange() {
            this.isLoading = true;

            this.form.post('/admin/searchArticlesByRange')
                .then(data => this.setArticlesData(data));
        },

        setArticlesData(articles) {
            this.isLoading = false;
            this.searchByArticlesData = this.editor.mapResultOfArticles(articles);
        },

        searchNow(data) {
            this.form['from'] = data.from;
            this.form['to'] = data.to;

            if (this.form.input.length > 0) {
                this.isLoading = true;

                if (this.searchBy === 'user') {
                    this.form.post('/admin/searchByUser').then(data => this.setArticlesData(data));
                } else if (this.searchBy === 'group') {
                    this.form.post('/admin/searchByGroup').then(data => this.setArticlesData(data));
                } else if (this.searchBy === 'website') {
                    this.form.post('/admin/searchByWebsite').then(data => this.setArticlesData(data));
                } else if (this.searchBy === 'keyword') {
                    this.form.post('/admin/searchByKeyword').then(data => this.setArticlesData(data));
                } else if (this.searchBy === 'title') {
                    this.form.post('/admin/searchByTitle').then(data => this.setArticlesData(data));
                }
            } else {
                new Noty({
                    type: 'error',
                    text: `Please enter your search in the input field.`,
                    layout: 'bottomLeft',
                    timeout: 5000
                }).show();
            }
        },

        changeSearch() {
            this.form.reset();
            this.initSetupDate();
        }
    }
});