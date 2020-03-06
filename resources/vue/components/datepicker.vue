<template>
    <div v-bind="rootProps">
        <date-input
            v-if="hasInput"
            :value="selectedDate"
            @input="handleInput"
            @toggle-datepicker="toggleDatepicker"
            v-bind="inputProps"
            ref="input"
        ></date-input>
        <div class="calendar" v-bind="calendarProps" ref="calendar" :class="align === 'left' ? 'align-left' : 'align-right'">
            <div class="calendar-nav navbar">
                <button class="btn btn-action btn-link btn-large prvMon" @click.stop="previousMonth"></button>
                <div class="month navbar-primary">{{ monthLabel }} {{ year }}</div>
                <button class="btn btn-action btn-link btn-large nxtMon" @click.stop="nextMonth"></button>
            </div>
            <div class="calendar-container">
                <div class="calendar-header">
                    <div v-for="weekday in weekdays" class="calendar-date">{{ weekday }}</div>
                </div>
                <div class="calendar-body">
                    <div v-for="day in days" class="calendar-date text-center" :class="['prev-month', '', 'next-month'][day.getMonth() - month + 1]" :key="day.getTime()">
                        <button
                            type="button"
                            class="date-item"
                            :class="{
                                'active': selectedDate && day.getTime() === selectedDate.getTime(),
                                'date-today': day.getTime() === today.getTime()
                            }"
                            :disabled="(validFrom && validFrom) > day || (validUntil && validUntil < day)"
                            @click.stop="(validFrom && validFrom) > day || (validUntil && validUntil < day) ? null : selectDate(day)"
                        >{{ day.getDate() }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import DateInput from './date-input';

    export default {
        components: {
            DateInput
        },

        data() {
            return {
                year: null,
                month: null,
                dateDay: null,
                selectedDate: null,
                expanded: !this.hasInput,
                align: 'left'
            };
        },

        watch: {
            value (newValue) {
                this.year = (newValue || this.today).getFullYear();
                this.month = (newValue || this.today).getMonth();
                this.dateDay = (newValue || this.today).getDate();
                this.selectedDate = newValue ? new Date(newValue.getFullYear(), newValue.getMonth(), newValue.getDate()) : null;
            },
            expanded (newValue) {
                if(newValue && this.hasInput) {
                    this.$nextTick(() =>
                         this.align = this.$refs.input.$el.getBoundingClientRect().left + this.$refs.calendar.getBoundingClientRect().width > window.innerWidth ? 'right' : 'left'
                    );
                }
            }
        },

        computed: {
            rootProps() {
                return {
                    class: ['datepicker', this.$attrs['class']],
                    style: { position: 'relative' }
                }
            },
            inputProps() {
                return {
                    style: { position: 'relative' },
                    inputFormat: this.$attrs['input-format'],
                    outputFormat: this.$attrs['output-format'],
                    dayNames: this.$attrs['day-names'],
                    monthNames: this.$attrs['month-names'],
                    showButton: this.$attrs['show-button']
                }
            },
            calendarProps() {
                return {
                    style: this.hasInput ? {
                        display: this.expanded ? 'block': 'none',
                        position: 'absolute',
                        top: '100%',
                        transform: 'translateY(.2rem)',
                        'z-index': 300
                    } : {}
                }
            },
            days() {
                const dates = [];
                const nextMonth = new Date(this.year, this.month + 1, 0);
                const preceedingDays = (new Date(this.year, this.month, 0)).getDay() + 1 - this.startOfWeekIndex;
                const trailingDays = (7 - nextMonth.getDay()) % 7 - 1 + this.startOfWeekIndex;

                for(let i = -preceedingDays, j = nextMonth.getDate() + trailingDays; i < j; ++i) {
                    dates.push(new Date(this.year, this.month, i + 1));
                }

                return (dates);
            },
            monthLabel() {
                return this.monthNames[this.month];
            },
            today() {
                const now = new Date();
                return new Date(now.getFullYear(), now.getMonth(), now.getDate());
            }
        },

        props: {
            value: {
                type: Date
            },
            validFrom: {
                type: Date
            },
            validUntil: {
                type: Date
            },
            weekdays: {
                type: Array,
                default: (() => "M T W T F S S".split(" "))
            },
            monthNames: {
                type: Array,
                default: (() => "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "))
            },
            startOfWeekIndex: {
                type: Number,
                default: 1,
                validator: value => !value || value === 1
            },
            hasInput: {
                type: Boolean,
                default: true
            }
        },

        mounted () {
            if(this.hasInput) {
                document.body.addEventListener('click', this.handleDocumentClick);
            }
            this.year = (this.value || this.today).getFullYear();
            this.month = (this.value || this.today).getMonth();
            this.dateDay = (this.value || this.today).getDate();
            this.selectedDate = this.value ? new Date(this.value.getFullYear(), this.value.getMonth(), this.value.getDate()) : null;
        },
        beforeDestroy() {
            if(this.hasInput) {
                document.body.removeEventListener('click', this.handleDocumentClick);
            }
        },

        methods: {
            previousMonth() {
                const d = new Date(this.year, this.month - 1, this.dateDay);
                this.month = d.getMonth();
                this.year = d.getFullYear();
                this.$emit("month-change");
            },
            nextMonth() {
                const d = new Date(this.year, this.month + 1, this.dateDay);
                this.month = d.getMonth();
                this.year = d.getFullYear();
                this.$emit("month-change");
            },
            selectDate(day) {
                this.selectedDate = day;
                this.$emit('input', day);
                this.expanded = !this.hasInput;
            },
            toggleDatepicker() {
                this.expanded = !this.expanded;
            },
            handleDocumentClick() {
                this.expanded = false;
            },
            handleInput (date) {
                this.selectedDate = date;
                this.$emit('input', date);
            }
        }
    }
</script>