<template>
    <div ref="container" class="modal modal-sm" :class="{ active: show }">
        <a href="#close" class="modal-overlay"></a>
        <div class="modal-container">
            <div class="modal-header" v-if="title">
                <div class="modal-title h5">{{ title }}</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    {{ message }}
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" @click.stop="ok" v-focus>{{ options.okLabel }}</button>
                <button class="btn btn-link" @click.stop="cancel">{{ options.cancelLabel }}</button>
            </div>
        </div>
    </div>
</template>

<script>
    /* heavily inspired by https://gist.github.com/eolant/ba0f8a5c9135d1a146e1db575276177d */

    import { Focus } from "../directives";

    export default {
        name: 'confirm',

        data () { return {
            title: "",
            message: "",
            show: false,
            resolve: null,
            reject: null,
            options: {
                okLabel: "Ok",
                cancelLabel: "Cancel"
            }
        }},

        methods: {
            open (title, message, options) {
                this.title = title;
                this.message = message;
                this.show = true;
                this.options = Object.assign(this.options, options || {});
                return new Promise((resolve, reject) => {
                    this.resolve = resolve;
                    this.reject = reject;
                });
            },
            ok () {
                this.show = false;
                this.resolve(true);
            },
            cancel () {
                this.show = false;
                this.resolve(false);
            }
        },

        directives: {
            focus: Focus
        }
    }
</script>