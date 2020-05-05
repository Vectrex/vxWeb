<template>
    <transition appear :name="transitionName">
        <div class="cookie-consent" :class="containerPosition" v-if="isOpen">
            <slot :accept="accept" :close="close" :decline="decline" :open="open">
                <div class="content">
                    <slot name="message">{{ message }}</slot>
                </div>
                <div class="buttons">
                    <a :target="target" :href="buttonLink" v-if="buttonLink" class="btn-link">{{ buttonLinkText }}</a>
                    <button @click="accept" class="btn-accept">{{ buttonText }}</button>
                    <button v-if="buttonDecline" @click="decline" class="btn-decline">{{ buttonDeclineText }}</button>
                </div>
            </slot>
        </div>
    </transition>
</template>

<script>
    import * as Cookie from '../util/cookie';

    export default {
        name: 'cookie-consent',
        props: {
            buttonText: {
                type: String,
                default: 'Ok'
            },
            buttonDecline: {
                type: Boolean,
                default: false
            },
            buttonDeclineText: {
                type: String,
                default: 'Decline'
            },
            buttonLink: {
                type: String,
                required: false
            },
            buttonLinkText: {
                type: String,
                default: 'More info'
            },
            buttonLinkNewTab: {
                type: Boolean,
                default: true
            },
            message: {
                type: String,
                default: 'This website uses cookies to ensure you get the best experience on our website.'
            },
            position: {
                type: String,
                default: 'bottom'
            },
            /**
             * options are: slideFromBottom, slideFromTop, fade
             */
            transitionName: {
                type: String,
                default: 'fade'
            },
            storageName: {
                type: String,
                default: 'cookie:accepted'
            },
            cookieOptions: {
                type: Object,
                default: () => {},
                required: false
            }
        },

        data () {
            return {
                isOpen: false
            }
        },

        computed: {
            containerPosition () {
                return this.position;
            },
            target () {
                return this.buttonLinkNewTab ? '_blank' : '_self';
            }
        },

        created () {
            if (!this.getVisited()) {
                this.isOpen = true;
            }
        },
        mounted () {
            if (this.isAccepted()) {
                this.$emit('accept');
            }
        },
        methods: {
            setVisited () {
                Cookie.set(this.storageName, true, { ...this.cookieOptions, expires: '1Y' })
            },
            setAccepted () {
                Cookie.set(this.storageName, true, { ...this.cookieOptions, expires: '1Y' })
            },
            setDeclined () {
                Cookie.set(this.storageName, false, { ...this.cookieOptions, expires: '1Y' })
            },
            getVisited () {
                let visited = false;
                visited = Cookie.get(this.storageName);
                if (typeof visited === 'string') {
                    visited = JSON.parse(visited);
                }
                return !(visited === null || visited === undefined);
            },
            isAccepted () {
                let accepted = false;
                accepted = Cookie.get(this.storageName);
                if (typeof accepted === 'string') {
                    accepted = JSON.parse(accepted);
                }
                return accepted;
            },
            accept () {
                this.setVisited();
                this.setAccepted();
                this.isOpen = false;
                this.$emit('accept');
            },
            close () {
                this.isOpen = false;
                this.$emit('close');
            },
            decline () {
                this.setVisited();
                this.setDeclined();
                this.isOpen = false;
                this.$emit('decline');
            },
            revoke () {
                Cookie.remove(this.storageName);
                this.isOpen = true;
                this.$emit('revoke');
            },
            open () {
                if (!this.getVisited()) {
                    this.isOpen = true;
                }
            }
        }
    }
</script>