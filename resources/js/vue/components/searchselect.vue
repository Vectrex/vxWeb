<template>
    <div :class="classes.wrapper">
        <div style="position: relative; width: 100%; display: inline-block;"  v-if="!selectedOption">
            <input ref="search"
                   :class="['form-input', isRequired]"
                   :id="inputId"
                   @keyup.enter="setOption"
                   @keyup.down="movePointerDown"
                   @keyup.tab.stop="closeOut"
                   @keyup.esc.stop="closeOut"
                   @keyup.up="movePointerUp"
                   :placeholder="placeholder"
                   autocomplete="off"
                   :required="required"
                   v-model="searchText"
            >

            <button type="button" @click="toggleDropdown" class="btn" style="position: absolute; top: 0; right: 0; background: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='#667189' d='M2 0L0 2h4zm0 5L0 3h4z'/>)</svg>\") no-repeat;">
                <i class="icon" :class="dropdownOpen ? 'icon-arrow-up' : 'icon-arrow-down'"></i>
            </button>

            <ul tabindex="-1" ref="options" v-if="matchingOptions"
                :style="{'max-height': maxHeight}" style="z-index: 100; width: 100%; overflow: auto; position: absolute;"
            >
                <li tabindex="-1"
                    v-for="(option, ndx) in matchingOptions" :key="ndx"
                    :class="ndx === pointer ? classes.activeClass : ''"
                    class="cursor-pointer outline-none"
                    @blur="handleClickOutside($event)"
                    @mouseover="setPointerIdx(ndx)"
                    @keyup.enter="setOption"
                    @click.prevent="setOption"
                    @keyup.up="movePointerUp"
                    @keyup.down="movePointerDown"
                >
                    <slot name="option" v-bind="{option, ndx}">
                        {{ getOptionDescription(option) }}
                    </slot>
                </li>
            </ul>
        </div>
        <div style="position: relative; width: 100%; display: inline-block;" v-else>
            <input :id="inputId"
                   :class="[classes.input]"
                   ref="match"
                   :required="required"
                   @input="switchToSearch($event)"
                   @click="switchToSearch($event)"
                   :value="getOptionDescription(selectedOption)"
            >
            <input type="hidden" :name="name" ref="selectedValue" :value="getOptionValue(selectedOption)">

            <div class="flex absolute items-center" :class="classes.icons">
                <button type="button" @click="closeOut" class="btn" style="position: absolute; top: 0; right: 0;">
                    <i class="icon icon-arrow-left"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>

    import pointerScroll from "./pointerScroll.js";

    export default {
        props: {
            value: {
                required: true
            },
            name: {
                type: String,
                required: false,
                default: () => ""
            },
            options: {
                type: Array,
                required: false,
                default: () => []
            },
            optionLabel: {
                type: String,
                required: false,
                default: () => ""
            },
            optionKey: {
                type: String,
                required: false,
                default: () => ""
            },
            placeholder: {
                type: String,
                required: false,
                default: () => "Search Here"
            },
            maxHeight: {
                type: String,
                default: () => "220px",
                required: false
            },
            inputId: {
                type: String,
                default: () => "single-select",
                required: false
            },
            classes: {
                type: Object,
                required: false,
                default: () => {
                    return {
                        pointer: -1,
                        wrapper: "single-select-wrapper",
                        input: "search-input",
                        icons: "icons",
                        required: "required",
                        activeClass: "active",
                        dropdown: "dropdown"
                    };
                }
            },
            initial: {
                type: String,
                required: false,
                default: () => ""
            },
            required: {
                type: Boolean,
                required: false,
                default: () => false
            },
            maxResults: {
                type: Number,
                required: false,
                default: () => 30
            },
            tabindex: {
                type: String,
                required: false,
                default: () => {
                    return "";
                }
            },
            getOptionDescription: {
                type: Function,
                default: function (option) {
                    if (this.optionKey && this.optionLabel) {
                        return option[this.optionKey] + " " + option[this.optionLabel];
                    }
                    if (this.optionLabel) {
                        return option[this.optionLabel];
                    }
                    if (this.optionKey) {
                        return option[this.optionKey];
                    }
                    return option;
                }
            },
            getOptionValue: {
                type: Function,
                default: function (option) {
                    if (this.optionKey) {
                        return option[this.optionKey];
                    }
                    if (this.optionLabel) {
                        return option[this.optionLabel];
                    }
                    return option;
                }
            },
            filterBy: {
                type: Function,
                default: function (option) {
                    if (this.optionLabel && this.optionKey) {
                        return (
                            option[this.optionLabel]
                            .toString()
                            .toLowerCase()
                            .includes(this.searchText.toString().toLowerCase()) ||
                            option[this.optionKey]
                            .toString()
                            .toLowerCase()
                            .includes(this.searchText.toString().toLowerCase())
                        )
                    }
                    if (this.optionLabel) {
                        return option[this.optionLabel]
                        .toString()
                        .toLowerCase()
                        .includes(this.searchText.toString().toLowerCase())
                    }
                    if (this.optionKey) {
                        option[this.optionKey]
                        .toString()
                        .toLowerCase()
                        .includes(this.searchText.toString().toLowerCase())
                    }
                    return option
                        .toString()
                        .toLowerCase()
                        .includes(this.searchText.toString().toLowerCase())
                }
            }
        },
        mixins: [pointerScroll],
        mounted() {
            document.addEventListener("click", this.handleClickOutside);
            document.addEventListener("keyup", this.handleClickOutside);
            if (this.value && this.options.includes(this.value)) {
                this.selectedOption = this.value;
            } else {
                this.searchText = this.initial;
            }
        },
        destroyed() {
            document.removeEventListener("keyup", this.handleClickOutside);
            document.removeEventListener("click", this.handleClickOutside);
        },
        data() {
            return {
            searchText: "",
            selectedOption: null,
            dropdownOpen: false
            };
        },
        watch: {
            value(curr) {
                this.selectedOption = curr;
            },
            searchText(curr, prev) {
                if (curr !== prev) {
                    this.pointer = -1;
                }
            },
            selectedOption(curr) {
                this.$emit("input", curr);
            },
            dropdownOpen(curr, prev) {
                if (curr !== prev) {
                    return;
                }
                if (!curr) {
                    this.searchText = "";
                    return;
                }
                this.$nextTick().then(() => {
                    this.$refs.search.focus();
                });
            }
        },
        computed: {
            isRequired() {
                if (!this.required) {
                    return "";
                }
                if (this.selectedOption) {
                    return "";
                }
                return "required";
            },
            matchingOptions() {
                if (this.searchText === null) {
                    return null;
                }
                if (!this.searchText.length) {
                    return [...this.options].slice(0, this.maxResults);
                }
                return this.options
                    .filter(option =>
                        this.filterBy(option)
                    )
                    .slice(0, this.maxResults);
                }
            },
        methods: {
            setPointerIdx(idx) {
                this.pointer = idx;
            },
            switchToSearch(event) {
                this.$refs.selectedValue.value = null;
                this.searchText = event.target.value;
                this.selectedOption = null;
                this.dropdownOpen = true;
            },
            toggleDropdown() {
                this.dropdownOpen = !this.dropdownOpen;
            },
            closeOut() {
                this.selectedOption = null;
                this.dropdownOpen = false;
                this.searchText = null;
            },
            movePointerDown() {
                if (this.matchingOptions) {
                    this.pointer = Math.min(this.pointer + 1, this.matchingOptions.length - 1);
                }
            },
            movePointerUp() {
                this.pointer = Math.max(0, this.pointer - 1);
            },
            setOption() {
                if (!this.matchingOptions || !this.matchingOptions.length) {
                    return;
                }
                if (this.pointer === -1) {
                    this.pointer++;
                }
                this.selectedOption = this.matchingOptions[this.pointer];
                this.searchText = null;
                this.dropdownOpen = false;
                this.pointer = -1;
                this.$nextTick().then(() => {
                    this.$refs.match.focus();
                });
            },
            handleClickOutside(e) {
                if (this.$el.contains(e.target)) {
                    return;
                }
                this.dropdownOpen = false;
                this.searchText = null;
            }
        }
    };
</script>
<!--
<style scoped>
 .w-full {
     width: 100%;
 }
 .inline-block {
     display: inline-block;
 }
 .block {
     display: block;
 }
 .flex {
     display: flex;
 }
 .border {
     border-width: thin;
     border-style: solid;
 }
 .rounded {
     border-radius: 0.25em;
 }
 .text-black {
     color: #22292f;
 }
 .border-grey-lighter {
     border-color: #ced4da;
 }
 .bg-grey-lighter {
     background-color: #606f7b;
 }
 .bg-grey-light {
     background-color: #dae1e7;
 }
 .bg-grey-dark {
     background-color: #8795a1;
 }
 .bg-white {
     background-color: #fff;
 }
 .pin-r {
     right: 0;
 }
 .pin-y {
     top: 0;
     bottom: 0;
 }
 .absolute {
     position: absolute;
 }
 .relative {
     position: relative;
 }
 .items-center {
     align-items: center;
 }
 .p-0 {
     padding: 0;
 }
 .p-1 {
     padding: 0.25em;
 }
 .px-1 {
     padding-left: 0.25em;
     padding-right: 0.25em;
 }
 .py-2 {
     padding-top: 0.5em;
     padding-bottom: 0.5em;
 }
 .px-2 {
     padding-left: 0.5em;
     padding-right: 0.5em;
 }
 .mt-px {
     margin-top: 1px;
 }
 .leading-tight {
     line-height: 1.25;
 }
 .leading-normal {
     line-height: 1.5;
 }
 .text-left {
     text-align: left;
 }
 .w-full {
     width: 100%;
 }
 .shadow {
     box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
 }
 .list-reset {
     list-style: none;
     padding: 0;
 }
 .overflow-auto {
     overflow: auto;
 }
 .appearance-none {
     -webkit-appearance: none;
     -moz-appearance: none;
     appearance: none;
 }
 .w-1 {
     width: 0.25em;
 }
 .w-2 {
     width: 0.5em;
 }
 .w-3 {
     width: 0.75em;
 }
 .w-4 {
     width: 1em;
 }
 .h-4 {
     height: 1em;
 }
 .h-1 {
     height: 0.25em;
 }
 .h-2 {
     height: 0.5em;
 }
 .h-3 {
     height: 0.75em;
 }
 .fill-current {
     fill: currentColor;
 }
 .no-underline {
     text-decoration: none;
 }
 .hover\:no-underline:hover {
     text-decoration: none;
 }
 .outline-none {
     outline: 0;
 }
 .hover\:outline-none {
     outline: 0;
 }
 .hover\:bg-grey-light:hover {
     background-color: #dae1e7;
 }
 .shadow-md {
     box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.12), 0 2px 4px 0 rgba(0, 0, 0, 0.08);
 }
 .search-input {
     display: block;
     width: 100%;
     padding: 0.375em 0.75em;
     font-size: 1em;
     line-height: 1.5;
     color: #495057;
     background-color: #fff;
     background-clip: padding-box;
     border: 1px solid #ced4da;
     border-radius: 0.25em;
     transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
     box-sizing: border-box;
 }
 .icons {
     padding: 0 1em;
     right: 0;
     top: 0;
     bottom: 0;
     fill: #606f7b;
 }
 .icons svg {
     width: 0.75em;
     height: 0.75em;
 }
 .single-select-wrapper {
     position: relative;
     margin-bottom: 0.5em;
 }
 .required {
     _color: #721c24;
     _background-color: #f8d7da;
     border-color: #f5c6cb;
 }
 .cursor-pointer {
     cursor: pointer;
 }
 .dropdown {
     -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.12),
     0 2px 4px 0 rgba(0, 0, 0, 0.08);
     box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.12), 0 2px 4px 0 rgba(0, 0, 0, 0.08);
     background-color: #fff;
     color: #606f7b;
     border-radius: 0.25em;
     line-height: 1.25;
     text-align: left;
 }
 .dropdown > li {
     padding: 0.5em 0.75em;
 }
 .active {
     background: #dae1e7;
 }
</style>
-->