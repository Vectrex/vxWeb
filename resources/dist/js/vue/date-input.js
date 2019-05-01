

    export default {
		template: '<div class="input-group input-inline"><input type="text" autocomplete="off" :class="computedClass" :value="formattedValue"><button v-if="showButton" type="button" class="btn webfont-icon-only calendarPopper btn-primary" @click="showDatepicker"></button></div>',

        data() {
            return {
                dateString: null
            }
        },

        props: {
            monthNames: {
                type: String,
                default: "Jan Feb Mar Apr Mai Jun Jul Aug Sep Okt Nov Dez"
            },
            dayNames: {
                type: String,
                default: "Mo Di Mi Do Fr Sa So"
            },
            dateFormat: {
                type: String,
                default: "%Y-%M-%D"
            },
            showButton: {
                type: Boolean,
                default: true
            },
            selectedDate: Date
        },

        computed: {

            computedClass() {
                return "form-input";
            },

            formattedValue() {
                if(this.selectedDate) {
                    return this.formatDate(this.selectedDate, this.dateFormat);
                }
            }

        },

        methods: {

            formatDate(date, format) {

                if (!date instanceof Date) {
                    return "";
                }

                return format
                    .replace("%d", date.getDate())
                    .replace("%D", ("0" + date.getDate()).slice(-2))
                    .replace("%m", date.getMonth() + 1)
                    .replace("%M", ("0" + (date.getMonth() + 1)).slice(-2))
                    .replace("%MMM", this.monthNames.split(" ")[date.getMonth()].trim())
                    .replace("%y", date.getFullYear().toString().slice(-2))
                    .replace("%Y", date.getFullYear())
                    .replace("%w", this.dayNames.split(" ")[date.getDay()].trim());
            },

            showDatepicker() {
                this.$emit("show-datepicker");
            }
        }
    }

