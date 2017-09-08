<template>
    <div class="overlay">
        <div class="KeywordAlert">
            <h2>Keyword Alert!</h2>
            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-hidden="true"
                @click="$emit('closeAlertBox')">
                &times;
            </button>

            <p>
                The keyword you type in is already in used. Please provide a unique one.
            </p>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>

<style scoped>
    .overlay {
        background: rgba(0,0,0,0.7);
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        z-index: 999999;
    }

    .KeywordAlert {
        background: #fff;
        position: relative;
        width: 500px;
        margin: 10em auto;
        padding: .1em 1em .5em;
    }

    button.close {
        opacity: 1;
        color: red;
        background: red;
        color: #fff;
        padding: 0.3em .5em;
        top: -.5em;
        right: -0.3em;
    }

    h2, p { color: red; }
    h2 { font-size: 3em; }
    p { font-size: 2.5em; }
</style>