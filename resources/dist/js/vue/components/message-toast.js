
    export default {
		template: '<div id="messageBox" :class="[{ &#39;display&#39;: isActive }, classname, &#39;toast&#39;]">{{ message }}<button class="btn btn-clear float-right" @click="isActive = false"></button></div>',

        data: () => ({
            activeTimeout: null,
            isActive: false
        }),

        props: {
            message: String,
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
