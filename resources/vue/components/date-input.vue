<template>
    <div class="date-input">
        <div class="input-group input-inline" :style="computedStyles">
            <div class="form-input">
                <div v-if="dateString">
                    <span class="chip">
                        {{ dateString }}
                        <a href="#" class="btn btn-clear" aria-label="Close" role="button" @click.prevent="handleClear"></a>
                    </span>
                </div>
                <input v-else
                    type="text"
                    autocomplete="off"
                    class="form-input"
                    v-model="inputString"
                    @blur="handleBlur"
                >
            </div>
            <button
                v-if="showButton"
                type="button"
                class="btn webfont-icon-only calendarPopper btn-primary"
                @click.stop="$emit('toggle-datepicker')"
            >&#xe00c;</button>
        </div>
    </div>
</template>

<script>
    import DateFunctions from "../util/date-functions.js";

    export default {

        data() {
            return {
                inputString: null
            }
        },

        props: {
            monthNames: {
                type: Array,
                default: () => "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" ")
            },
            dayNames: {
                type: Array,
                default: () => "Mon Tue Wed Thu Fri Sat Sun".split(" ")
            },
            outputFormat: {
                type: String,
                default: "y-mm-dd"
            },
            inputFormat: {
                type: String,
                default: 'y-m-d'
            },
            showButton: {
                type: Boolean,
                default: true
            },
            value: Date
        },

        watch: {
            value (value) {
                this.inputString = value ? DateFunctions.formatDate(value, this.outputFormat) : '';
            }
        },

        computed: {
            dateString () {
                return this.value ? DateFunctions.formatDate(this.value, this.outputFormat) : '';
            },
            computedStyles() {
                return {
                    width: '100%'
                }
            }
        },

        methods: {
            handleBlur () {
                let date = DateFunctions.parseDate(this.inputString, this.inputFormat);
                this.$emit('input', date || null);
            },
            handleClear () {
                this.$emit('input', null);
            }
        }
    }
</script>