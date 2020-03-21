<template>
    <svg :height="size" :width="size" class="circular-progress">
        <circle
            stroke="white"
            fill="transparent"
            :stroke-width="strokeWidth"
            :r="normalizedRadius"
            :cx="radius"
            :cy="radius"
        />
        <circle
            :stroke="color"
            fill="transparent"
            :stroke-dasharray="circumference + ' ' + circumference"
            :style="{ strokeDashoffset }"
            :stroke-width="strokeWidth"
            :r="normalizedRadius"
            :cx="radius"
            :cy="radius"
        />
    </svg>
</template>

<script>
    export default {
        name: "circular-progress",

        props: {
            progress: Number,
            radius: { type: Number, default: 24 },
            strokeWidth: { type: Number, default: 8 },
            color: { type: String, default: 'white' }
        },

        computed: {
            size () {
                return 2 * this.radius;
            },
            normalizedRadius () {
                return this.radius - this.strokeWidth / 2;
            },
            circumference () {
                return this.normalizedRadius * 2 * Math.PI;
            },
            strokeDashoffset () {
                return this.circumference - this.progress / 100 * this.circumference;
            }
        }
    }
</script>
