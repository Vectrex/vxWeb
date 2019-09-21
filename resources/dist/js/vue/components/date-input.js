
    import DateFunctions from "../mixins/date-functions.js";

    export default {
		template: '<div class="date-input"><div class="input-group input-inline" :style="computedStyles"><div class="form-input"><div v-if="dateString"><span class="chip">{{ dateString }}<a href="#" class="btn btn-clear" aria-label="Close" role="button" @click.prevent="$emit(&#39;date-clear&#39;)"></a></span></div><input v-else="" type="text" autocomplete="off" class="form-input" :value="formattedValue" @blur="$emit(&#39;dateinput-blur&#39;, $event.target.value)"></div><button v-if="showButton" type="button" class="btn webfont-icon-only calendarPopper btn-primary" @click.stop="$emit(&#39;toggle-datepicker&#39;)"></button></div></div>',

        mixins: [ DateFunctions ],

        data() {
            return {
                dateString: null,
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
            outputFormat: {
                type: String,
                default: "y-mm-dd"
            },
            showButton: {
                type: Boolean,
                default: true
            },
            date: Date
        },

        computed: {
            formattedValue() {
                if(this.date) {
                    return this.formatDate(this.date, this.outputFormat);
                }
            },
            computedStyles() {
                return {
                    width: '100%'
                }
            }
        },

        watch: {
            date(newValue) {
                this.dateString = newValue ? this.formatDate(newValue, this.outputFormat) : '';
            }
        },

        methods: {
        }
    }