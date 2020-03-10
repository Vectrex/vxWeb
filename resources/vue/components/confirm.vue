<template>
    <div ref="container" class="modal" :class="{ active: show }">
        <a href="#close" class="modal-overlay" aria-label="Close" @click.prevent="$emit('close')"></a>
        <div class="modal-container">
            <div class="modal-header">
                <a href="#close" class="btn btn-clear float-right" aria-label="Close" @click.prevent="$emit('close')"></a>
                <div class="modal-title h5">{{ title }}</div>
            </div>
            <div class="modal-body">
                <div class="content">
                    {{ content }}
                </div>
            </div>
            <div class="modal-footer">
                <button
                    v-for="(button, ndx) in buttons"
                    @click.prevent="handleButtonClick(button)"
                    :key="ndx"
                    v-focus-first="ndx"
                    type="button"
                    class="btn"
                    :class="button.class || ''"
                >{{ button.label }}</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'confirm',
        props: {
            show: {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: "Sure?"
            },
            message: String,
            buttons: {
                type: Array,
                default: [ { value: 'no', label: 'No' }, { value: 'yes', label: 'Yes' } ]
            },

            mounted() {
                ['click', 'touchstart'].forEach(e => document.body.addEventListener(e, this.handleDocumentClick));
            },
            beforeDestroy() {
                ['click', 'touchstart'].forEach(e => document.body.removeEventListener(e, this.handleDocumentClick));
            },

            methods: {
                handleDocumentClick(event) {
                    if (this.$refs.container.contains(event.target)) {
                        return;
                    }
                    this.$emit('close');
                },
                handleButtonClick(data) {
                    this.$emit('button-click', data.value);
                }
            },

            directives: {
                focusFirst: {
                    inserted(el, binding) {
                        if(binding.expression === 0) {
                            el.focus();
                        }
                    }
                }
            }
        }
    }
</script>