<template>
    <div class="date-input">
        <div class="input-group input-inline">
            <div class="form-input">
                <span v-if="dateString" class="chip">
                    {{ dateString }}
                    <a href="#" class="btn btn-clear" aria-label="Close" role="button" @click="clearDate"></a>
                </span>
                <input v-else
                    type="text"
                    autocomplete="off"
                    :class="computedClass"
                    :value="formattedValue"
                    @focus="handleFocus"
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
    export default {

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
            handleBlur () {
            },
            handleFocus () {
                this.error = false;
            },
            clearDate () {
                this.dateString = null;
            },
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
                posMap.push( { srcPos: format.toLowerCase().indexOf('d'), destPos: 2 });

                if((matches = format.match(/\bm\b/gi)) && 1 === matches.length) {
                    escapedFormat = escapedFormat.replace('m', '(\\d{1,2})');
                }
                else if((matches = format.match(/\bmm\b/gi)) && 1 === matches.length) {
                    escapedFormat = escapedFormat.replace('mm', '(\\d{2})');
                }
                else {
                    return false;
                }
                posMap.push( { srcPos: format.toLowerCase().indexOf('m'), destPos: 1 });

                if((matches = format.match(/\by\b/gi)) && 1 === matches.length) {
                    escapedFormat = escapedFormat.replace('y', '(\\d{4})');
                }
                else {
                    return false;
                }
                posMap.push( { srcPos: format.toLowerCase().indexOf('y'), destPos: 0 });

                if(!(matches = dateString.match(escapedFormat))) {
                    return false;
                }

                // remove first match

                matches.shift();

                // bring day, month, year in correct order to allow ISO notation

                posMap.sort( (a, b) => a.srcPos < b.srcPos ? -1 : 1);

                let result = [], part, pos;

                while((part = matches.shift())) {
                    pos = posMap.shift();
                    result[pos.destPos] = part;
                }

                result = Date.parse(result.join('-'));

                return result ? new Date(result) : false;
            }
        }
    }
</script>