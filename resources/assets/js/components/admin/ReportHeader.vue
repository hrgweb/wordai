<template>
    <div class="ReportHeader text-center">
        <h2>
            <slot name="head"></slot>
            <span class="badge">{{ count }}</span>
        </h2>
        <p>
            <span>From: <b>{{ report.fromUtc }}</b></span> -
            <span>To: <b>{{ report.toUtc }}</b></span>
        </p>
    </div>
</template>

<script>
    export default {
        props: ['count'],
        data() {
            return { report: ReportingBus }
        },
        mounted() {
            this.report.setDayFromMonToSun();
        }
    }
</script>