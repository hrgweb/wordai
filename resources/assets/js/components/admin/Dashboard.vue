<template>
    <div class="Dashboard">
        <!-- Article Report -->
        <article-report
            :articles="articles"
            :fromUtc="fromUtc"
            :toUtc="toUtc">
        </article-report>

        <!-- Article Edited -->
        <article-edited
            :articles="noOfArticlesEditedThisWeek"
            :fromUtc="fromUtc"
            :toUtc="toUtc">
        </article-edited>

        <!-- ArticleToEdit -->
        <article-to-edit
            :articles="noOfArticlesToEditThisWeek"
            :fromUtc="fromUtc"
            :toUtc="toUtc">
        </article-to-edit>

        <!-- Pending User -->
        <pending-user :token="token"></pending-user>
    </div>
</template>

<script>
    import ArticleReport from './ArticleReport.vue';
    import ArticleEdited from './ArticleEdited.vue';
    import ArticleToEdit from './ArticleToEdit.vue';
    import PendingUser from './PendingUser.vue';

    export default {
        props: ['token'],
        components: { ArticleReport, ArticleEdited, ArticleToEdit, PendingUser },
        data() {
            return {
                articles: [],
                noOfArticlesEditedThisWeek: 0,
                noOfArticlesToEditThisWeek: 0,
                date: {
                    fromMon: 0,
                    toSun: 0,
                    curMonth: 0,
                    curYear: 0,
                    monthSame: false
                },
                fromUtc: '',
                toUtc: '',
                isSameMonth: false
            };
        },
        watch: {
            date() {
                this.articlesThisWeek();
            },

            articles(data) {
                this.articlesEditedThisWeek();
                this.articlesWaitToEdit();
            }
        },
        mounted() {
            this.setDayFromMonToSun();
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

                // check if month is = if not substract 1
                let month = 0;

                if (firstday.toLowerCase() === lastday.toLowerCase()) {
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

                this.date['monthSame'] = this.isSameMonth;
            },

            paramsForDate() {
                let date = this.date;

                if (this.isSameMonth === true) {
                    return '?fromMon='+date.fromMon+'&toSun='+date.toSun+'&curMonth='+date.curMonth+'&curYear='+date.curYear+'&monthSame='+date.monthSame;
                } else {
                    return '?fromMon='+date.fromMon+'&toSun='+date.toSun+'&curMonthMon='+date.curMonthMon+'&curMonthSun='+date.curMonthSun+'&curYear='+date.curYear+'&monthSame='+date.monthSame;
                }

            },

            articlesThisWeek() {
                let params = this.paramsForDate();

                axios.get('/admin/articlesThisWeek'+params).then(response => {
                    const data = response.data;

                    if (data) {
                        // map results
                        this.articles = data.map(item => {
                            return {
                                word_id: item.id,
                                doc_title: item.doc_title,
                                keyword: item.keyword,
                                article: item.article.substr(0, 200) + '...',
                                created_at: item.created_at,
                                isEditorEdit: item.isEditorEdit
                            };
                        });
                    }
                });
            },

            articlesEditedThisWeek() {
                this.noOfArticlesEditedThisWeek = this.articles.filter(item => {
                    return item.isEditorEdit === 1;
                });
            },

            articlesWaitToEdit() {
                console.log(this.articles);
                this.noOfArticlesToEditThisWeek = this.articles.filter(item => {
                    return item.isEditorEdit === 0;
                });
            }
        }
    }
</script>

<style scoped>
    .Dashboard {
        padding: 0 1em;
        margin-bottom: 10em;
    }
</style>