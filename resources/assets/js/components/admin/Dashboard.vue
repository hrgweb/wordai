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

        <!-- Pending User -->
        <pending-user :token="token"></pending-user>
    </div>
</template>

<script>
    import ArticleReport from './ArticleReport.vue';
    import ArticleEdited from './ArticleEdited.vue';
    import PendingUser from './PendingUser.vue';

    export default {
        props: ['token'],
        components: { ArticleReport, ArticleEdited, PendingUser },
        data() {
            return {
                articles: [],
                noOfArticlesEditedThisWeek: 0,
                date: {
                    fromMon: 0,
                    toSun: 0,
                    curMonth: 0,
                    curYear: 0
                },
                fromUtc: '',
                toUtc: ''
            };
        },
        watch: {
            date() {
                this.articlesThisWeek();
            },

            articles(data) {
                this.articlesEditedThisWeek();
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
                this.date = {
                    fromMon: mon,
                    toSun: sun,
                    curMonth: curr.getMonth() + 1,
                    curYear: curr.getFullYear()
                };
            },

            paramsForDate() {
                let date = this.date;

                return '?fromMon='+date.fromMon+'&toSun='+date.toSun+'&curMonth='+date.curMonth+'&curYear='+date.curYear;
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