
    export default {
		template: '<div id="messageBox" :class="[{ &#39;display&#39;: localState }, classname, &#39;toast&#39;]">{{ message }}<button class="btn btn-clear float-right" @click="localState = false"></button></div>',
        created() {
            this.vars = {
                timeoutId: null
            }
        },
        data: function() {
            return {
                localState: this.state
            };
        },
        props: [
            'message',
            'classname',
            'state'
        ],
        watch: {
            state: function(newVal) {
                this.localState = newVal;

                if(this.vars.timeoutId) {
                    window.clearTimeout(this.vars.timeoutId);
                }
                this.vars.timeoutId = window.setTimeout(() => { this.localState = false }, 5000);
            }
        }
    }
