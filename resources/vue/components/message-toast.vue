<template>
    <div id="messageBox" :class="[{ 'display': isActive }, classname, 'toast']">
        <button class="btn btn-clear float-right" @click="isActive = false"></button>
        <div v-for="line in lines">{{ line }}</div>
    </div>
</template>

<script>
    export default {

        data: () => ({
            activeTimeout: null,
            isActive: false
        }),

        props: {
            message: [String, Array],
            classname: String,
            timeout: {
                type: Number,
                default: 5000
            },
            active: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            lines () {
                return typeof this.message === 'string' ? [this.message] : this.message;
            }
        },

        watch: {
            active (state) {
                this.isActive = state;
            },
            isActive () {
                this.setTimeout();
            }
        },

        mounted () {
            this.setTimeout();
        },

        methods: {
            setTimeout() {
                window.clearTimeout(this.activeTimeout);

                // timeout 0 disables fadeout

                if (this.isActive && this.timeout) {
                    this.activeTimeout = window.setTimeout( () => {
                        this.isActive = false;
                    }, this.timeout);
                }
            }
        }
    }
</script>