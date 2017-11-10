<template>
    <div class="prepost">
        <h4>PrepostSEO Result</h4><hr>
        
        <!-- info -->
        <ul class="prepost nav inline-block">
            <li>Plagiarism: <b>{{ prepost.plagPercent }}</b>%</li>
            <li>Uniqueness: <b>{{ prepost.uniquePercent }}</b>%</li>
        </ul><br>

        <!-- result -->
        <ul class="result nav">
            <li v-for="(item, index) in prepost.details" :key="index">
                <div class="item alert" :class="{ 'alert-success': item.unique === 'true', 'alert-danger': item.unique === 'false' }">
                    <p>{{ item.query }}</p>
                    <ul class="url" v-if="item.matched_urls.length > 0">
                        <li v-for="(url, index) in item.matched_urls" :key="index">
                            <a :href="url" target="_blank">{{ url }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        
        <!-- <pre>{{ prepost }}</pre> -->
    </div>
</template>

<script>
    export default {
        props: ['prepost']
    }
</script>

<style lang="scss" scoped>
    ul.prepost {

        li {
            display: inline-block;
            margin-right: 2em;
        }
    }

    div.prepost {
        background: #787879;
        color: #fff;
        padding: .5em 1.5em;
        border: 1px solid #dad4d4;
    }

    div.item {
        margin: 0;

        p { font-size: 1.1em; }
    }

    ul.url {
        margin-top: 1em;

        a { color: #00f1ff !important; }
    }

    ul.result {
        li { margin-bottom: .3em !important; }
    }

    .alert-success,
    .alert-danger {
        color: #fff !important;
        border-radius: 0 !important;
    }

    .alert-success {
        background-color: #5bc530 !important;
        border-color: #4fab2a !important;
    }

    .alert-danger {
        background-color: #d44f4f !important;
        border-color: #b94747 !important;
    }
</style>
