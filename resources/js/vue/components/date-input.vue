<template>
    <div class="date-input">
        <div class="input-group input-inline">
            <div class="form-input">
                <span v-if="dateString" class="chip">
                    {{ dateString }}
                    <a href="#" class="btn btn-clear" aria-label="Close" role="button" @click.prevent="clearDate"></a>
                </span>
                <input v-else
                    type="text"
                    autocomplete="off"
                    :class="computedClass"
                    :value="formattedValue"
                    @focus="handleFocus"
                    @blur="$emit('dateinput-blurred', $event.target.value)"
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
    import DateFunctions from "./mixins/date-functions.js";

    export default {

        mixins: [ DateFunctions ],

        data() {
            return {
                dateString: null,
                error: false,
                parsedDate: null
            }
        },

        props: {
            monthNames: {
                type: Array,
                default: () => "Jan Feb Mar Apr Mai Jun Jul Aug Sep Okt Nov Dez".split(" ")
            },
            dayNames: {
                type: Array,
                default: () => "Mo Di Mi Do Fr Sa So".split(" ")
            },
            inputFormat: {
                type: String,
                default: "Y-M-D"
            },
            outputFormat: {
                type: String,
                default: "%Y-%M-%D"
            },
            showButton: {
                type: Boolean,
                default: true
            },
            date: Date
        },

        computed: {

            computedClass() {
                return "form-input";
            },

            formattedValue() {
                if(this.date) {
                    return this.formatDate(this.date, this.outputFormat);
                }
            }

        },

        watch: {
            date(newValue) {
                this.dateString = this.formatDate(newValue, this.outputFormat);
            }
        },

        methods: {
            handleFocus () {
                this.error = false;
            },
            clearDate () {
                this.dateString = null;
            }
        }
    }
</script>