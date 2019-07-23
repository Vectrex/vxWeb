
    export default {
		template: '<div class="input-group input-inline"><input type="text" autocomplete="off" :class="computedClass" :value="formattedValue"><button v-if="showButton" type="button" class="btn webfont-icon-only calendarPopper btn-primary" @click="$emit(&#39;toggle-datepicker&#39;)"></button></div>',

        data() {
            return {
                dateString: null
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
            dateFormat: {
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
                    return this.formatDate(this.date, this.dateFormat);
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
                    .replace("%MMM", this.monthNames[date.getMonth()].trim())
                    .replace("%y", date.getFullYear().toString().slice(-2))
                    .replace("%Y", date.getFullYear())
                    .replace("%w", this.dayNames[date.getDay()].trim());
            },

            parseDate(dateString, format) {

                let matches, escapedFormat = format.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), posMap = [];
                const date = new Date();

                // check for single day, month and year expression

                if((matches = format.match(/\bd\b/gi)) && 1 === matches.length) {
                    escapedFormat = escapedFormat.replace('d', '(\\d{1,2})');
                }
                else if((matches = format.match(/\bdd\b/gi)) && 1 === matches.length) {
                    escapedFormat = escapedFormat.replace('dd', '(\\d{2})');
                }
                else {
                    return false;
                }
                posMap[2] = format.toLowerCase().indexOf('d');

                if((matches = format.match(/\bm\b/gi)) && 1 === matches.length) {
                    escapedFormat = escapedFormat.replace('m', '(\\d{1,2})');
                }
                else if((matches = format.match(/\bmm\b/gi)) && 1 === matches.length) {
                    escapedFormat = escapedFormat.replace('mm', '(\\d{2})');
                }
                else {
                    return false;
                }
                posMap[1] = format.toLowerCase().indexOf('m');

                if((matches = format.match(/\by\b/gi)) && 1 === matches.length) {
                    escapedFormat = escapedFormat.replace('y', '(\\d{4})');
                }
                else {
                    return false;
                }
                posMap[0] = format.toLowerCase().indexOf('y');

                if(!(matches = dateString.match(escapedFormat))) {
                    return false;
                }

                // remove first match

                matches.shift();

                /*
                 e.g.
                 [y, m, d] -
                 [4, 0, 2] => [2, 0, 1] (m/d/y)
                 [4, 2, 0] => [2, 1, 0] (d.m.y)
                 [0, 6, 4] => [0, 2, 1] (y.d.m)
                 */

            }
        }
    }
