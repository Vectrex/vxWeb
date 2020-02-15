module.exports =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "fae3");
/******/ })
/************************************************************************/
/******/ ({

/***/ "f6fd":
/***/ (function(module, exports) {

// document.currentScript polyfill by Adam Miller

// MIT license

(function(document){
  var currentScript = "currentScript",
      scripts = document.getElementsByTagName('script'); // Live NodeList collection

  // If browser needs currentScript polyfill, add get currentScript() to the document object
  if (!(currentScript in document)) {
    Object.defineProperty(document, currentScript, {
      get: function(){

        // IE 6-10 supports script readyState
        // IE 10+ support stack trace
        try { throw new Error(); }
        catch (err) {

          // Find the second match for the "at" string to get file src url from stack.
          // Specifically works with the format of stack traces in IE.
          var i, res = ((/.*at [^\(]*\((.*):.+:.+\)$/ig).exec(err.stack) || [false])[1];

          // For all scripts on the page, if src matches or if ready state is interactive, return the script tag
          for(i in scripts){
            if(scripts[i].src == res || scripts[i].readyState == "interactive"){
              return scripts[i];
            }
          }

          // If no match, return null
          return null;
        }
      }
    });
  }
})(document);


/***/ }),

/***/ "fae3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  if (true) {
    __webpack_require__("f6fd")
  }

  var i
  if ((i = window.document.currentScript) && (i = i.src.match(/(.+\/)[^/]+\.js(\?.*)?$/))) {
    __webpack_require__.p = i[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/message-toast.vue?vue&type=template&id=2342e266&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{class:[{ 'display': _vm.isActive }, _vm.classname, 'toast'],attrs:{"id":"messageBox"}},[_vm._v(" "+_vm._s(_vm.message)+" "),_c('button',{staticClass:"btn btn-clear float-right",on:{"click":function($event){_vm.isActive = false}}})])}
var staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/message-toast.vue?vue&type=template&id=2342e266&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/message-toast.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//

/* harmony default export */ var message_toastvue_type_script_lang_js_ = ({

    data: () => ({
        activeTimeout: null,
        isActive: false
    }),

    props: {
        message: String,
        classname: String,
        timeout: {
            type: Number,
            default: 5000
        },
        active: {
            type: Boolean,
            default: false
        }
    },

    watch: {
        active (state) {
            this.isActive = state;
        },
        isActive () {
            this.setTimeout();
        }
    },

    mounted () {
        this.setTimeout();
    },

    methods: {
        setTimeout() {
            window.clearTimeout(this.activeTimeout);

            // timeout 0 disables fadeout

            if (this.isActive && this.timeout) {
                this.activeTimeout = window.setTimeout( () => {
                    this.isActive = false;
                }, this.timeout);
            }
        }
    }
});

// CONCATENATED MODULE: ./vue/components/message-toast.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_message_toastvue_type_script_lang_js_ = (message_toastvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}

// CONCATENATED MODULE: ./vue/components/message-toast.vue





/* normalize component */

var component = normalizeComponent(
  components_message_toastvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var message_toast = (component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/autocomplete.vue?vue&type=template&id=093bb56e&
var autocompletevue_type_template_id_093bb56e_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',_vm._b({ref:"container"},'div',_vm.containerProps,false),[_c('input',_vm._g(_vm._b({ref:"input",on:{"input":_vm.handleInput,"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.handleEnter($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }return _vm.handleEsc($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"tab",9,$event.key,"Tab")){ return null; }return _vm.handleTab($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"up",38,$event.key,["Up","ArrowUp"])){ return null; }$event.preventDefault();return _vm.handleUp($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"down",40,$event.key,["Down","ArrowDown"])){ return null; }$event.preventDefault();return _vm.handleDown($event)}],"focus":_vm.handleFocus,"blur":_vm.handleBlur}},'input',_vm.inputProps,false),_vm.$listeners)),_c('ul',_vm._b({ref:"resultList",on:{"click":_vm.handleResultClick,"mousedown":function($event){$event.preventDefault();}}},'ul',_vm.resultListProps,false),[_vm._l((_vm.results),function(result,index){return [_vm._t("result",[_c('li',_vm._b({key:_vm.resultProps[index].id},'li',_vm.resultProps[index],false),[_vm._v(" "+_vm._s(_vm.getResultValue(result))+" ")])],{"result":result,"props":_vm.resultProps[index]})]})],2)])}
var autocompletevue_type_template_id_093bb56e_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/autocomplete.vue?vue&type=template&id=093bb56e&

// CONCATENATED MODULE: ./vue/util/closest-polyfill.js
/* harmony default export */ var closest_polyfill = ({});

if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}

if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        let el = this;

        do {
            if (el.matches(s)) {
                return el;
            }
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/autocomplete.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




let uniqueId = function() {
  let counter = 0;
  return function(prefix) {
    return (prefix || "") + ++counter;
  }
}();

/* harmony default export */ var autocompletevue_type_script_lang_js_ = ({
  name: 'Autocomplete',
  inheritAttrs: false,

  props: {
    search: {
      type: Function,
      required: true
    },
    baseClass: {
      type: String,
      default: 'form-autocomplete'
    },
    resultListClass: {
      type: String,
      default: 'menu'
    },
    resultClass: {
      type: String,
      default: 'menu-item'
    },
    inputClass: {
      type: String,
      default: 'autocomplete-input form-input'
    },
    autoSelect: {
      type: Boolean,
      default: false
    },
    getResultValue: {
      type: Function,
      default: result => result
    },
    defaultValue: {
      type: String,
      default: ""
    },
  },

  data() {
    return {
      value: this.defaultValue,
      resultListId: uniqueId(this.resultListClass + "-"),
      results: [],
      selectedIndex: -1,
      searchCounter: 0,
      expanded: false,
      loading: false,
      position: 'below',
      resetPosition: true
    }
  },

  watch: {
    defaultValue (newValue) {
      this.value = newValue;
    }
  },

  computed: {
    containerProps() {
      return {
        class: this.baseClass,
        style: { position: 'relative' },
        'data-expanded': this.expanded,
        'data-loading': this.loading,
        'data-position': this.position
      }
    },
    inputProps() {
      return {
        class: this.inputClass,
        value: this.value,
        role: 'combobox',
        autocomplete: 'off',
        autocapitalize: 'off',
        autocorrect: 'off',
        spellcheck: 'false',
        'aria-autocomplete': 'list',
        'aria-haspopup': 'listbox',
        'aria-owns': this.resultListId,
        'aria-expanded': this.expanded ? 'true' : 'false',
        'aria-activedescendant': this.selectedIndex > -1 ? this.resultProps[this.selectedIndex].id : '',
        ...this.$attrs
      }
    },
    resultListProps() {
      return {
        id: this.resultListId,
        class: this.resultListClass,
        role: 'listbox'
      }
    },
    resultProps() {
      return this.results.map((result, index) => ({
        id: this.resultClass + "-" + index,
        class: this.resultClass,
        'data-result-index': index,
        role: 'option',
        ...(this.selectedIndex === index ? { 'aria-selected': 'true' } : {})
      }))
    },
  },

  mounted() {
    document.body.addEventListener('click', this.handleDocumentClick);
  },

  beforeDestroy() {
    document.body.removeEventListener('click', this.handleDocumentClick);
  },

  updated() {
    if (!this.$refs.input || !this.$refs.resultList) {
      return;
    }

    let inputPos = this.$refs.input.getBoundingClientRect();
    let listPos = this.$refs.resultList.getBoundingClientRect();

    if (this.resetPosition && this.results.length) {

      this.resetPosition = false;

      // show list above or below

      this.position = (inputPos.bottom + listPos.height > window.innerHeight) && (window.innerHeight - inputPos.bottom < inputPos.top) && (window.pageYOffset + inputPos.top - listPos.height > 0) ? "above" : "below";

    }

    // Make sure selected result isn't scrolled out of view

    let selectedElem = this.$refs.resultList.querySelector('[data-result-index="' + this.selectedIndex + '"]');

    if (!selectedElem) {
      return;
    }

    let selectedPos = selectedElem.getBoundingClientRect();

    if (selectedPos.top < listPos.top) {
      // Element is above viewable area
      this.$refs.resultList.scrollTop -= listPos.top - selectedPos.top;
    }
    else if (selectedPos.bottom > listPos.bottom) {
      // Element is below viewable area
      this.$refs.resultList.scrollTop += selectedPos.bottom - listPos.bottom;
    }
  },

  methods: {
    setValue (result) {
      this.value = result ? this.getResultValue(result) : '';
    },

    handleInput (event) {
      this.value = event.target.value;
      this.updateResults(this.value);
    },

    handleFocus (event) {
      this.updateResults(event.target.value);
      this.value = event.target.value;
    },

    handleBlur () {
      this.hideResults();
      this.$emit ('blur');
    },

    handleUp () {
      const listLen = this.results.length;
      this.selectedIndex = (((this.selectedIndex - 1) % listLen) + listLen) % listLen;
    },

    handleDown (event) {
      if(!this.expanded) {
        this.handleFocus(event);
      }
      const listLen = this.results.length;
      this.selectedIndex = (((this.selectedIndex + 1) % listLen) + listLen) % listLen;
    },

    handleEsc () {
      this.hideResults();
      this.setValue();
    },

    handleEnter () {
      this.handleSubmit(this.selectResult());
    },

    handleTab () {
      this.selectResult();
    },

    hideResults () {
      this.selectedIndex = -1;
      this.results = [];
      this.expanded = false;
      this.resetPosition = true;
    },

    selectResult () {
      const selectedResult = this.results[this.selectedIndex];
      if (selectedResult) {
        this.setValue(selectedResult);
      }
      this.hideResults();
      return selectedResult;
    },

    handleSubmit (selectedResult) {
      this.$emit('submit', selectedResult);
    },

    handleResultClick (event) {
      const result = event.target.closest('[data-result-index]');
      if (result) {
        this.selectedIndex = parseInt(result.dataset.resultIndex, 10);
        this.handleSubmit(this.selectResult());
      }
    },

    handleDocumentClick (event) {
      if (this.$refs.container.contains(event.target)) {
        return;
      }
      this.hideResults();
    },

    updateResults: function (value) {

      const search = this.search(value);

      if (search instanceof Promise) {

        const currentSearch = ++this.searchCounter;
        this.loading = true;

        search.then(results => {
          if (currentSearch !== this.searchCounter) {
            return;
          }
          this.results = results;
          this.loading = false;

          if (!this.results.length) {
            this.hideResults();
          } else {
            this.selectedIndex = this.autoSelect ? 0 : -1;
            this.expanded = true;
          }
        });

      } else {

        this.results = search;

        if (this.results.length === 0) {
          this.hideResults();
        } else {
          this.selectedIndex = this.autoSelect ? 0 : -1;
          this.expanded = true;
        }
      }
    }

  }
});

// CONCATENATED MODULE: ./vue/components/autocomplete.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_autocompletevue_type_script_lang_js_ = (autocompletevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/autocomplete.vue





/* normalize component */

var autocomplete_component = normalizeComponent(
  components_autocompletevue_type_script_lang_js_,
  autocompletevue_type_template_id_093bb56e_render,
  autocompletevue_type_template_id_093bb56e_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var autocomplete = (autocomplete_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/datepicker.vue?vue&type=template&id=a5f44e50&
var datepickervue_type_template_id_a5f44e50_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',_vm._b({},'div',_vm.rootProps,false),[(_vm.hasInput)?_c('date-input',_vm._b({ref:"input",attrs:{"date":_vm.selectedDate,"output-format":_vm.$attrs['output-format'],"day-names":_vm.$attrs['day-names'],"show-button":_vm.$attrs['show-button'],"month-name":_vm.$attrs['month-names']},on:{"toggle-datepicker":_vm.toggleDatepicker,"dateinput-blur":_vm.updateDate,"date-clear":_vm.clearDate}},'date-input',_vm.inputProps,false)):_vm._e(),_c('div',_vm._b({staticClass:"calendar"},'div',_vm.calendarProps,false),[_c('div',{staticClass:"calendar-nav navbar"},[_c('button',{staticClass:"btn btn-action btn-link btn-large prvMon",on:{"click":function($event){$event.stopPropagation();return _vm.previousMonth($event)}}}),_c('div',{staticClass:"month navbar-primary"},[_vm._v(_vm._s(_vm.monthLabel)+" "+_vm._s(_vm.year))]),_c('button',{staticClass:"btn btn-action btn-link btn-large nxtMon",on:{"click":function($event){$event.stopPropagation();return _vm.nextMonth($event)}}})]),_c('div',{staticClass:"calendar-container"},[_c('div',{staticClass:"calendar-header"},_vm._l((_vm.weekdays),function(weekday){return _c('div',{staticClass:"calendar-date"},[_vm._v(_vm._s(weekday))])}),0),_c('div',{staticClass:"calendar-body"},_vm._l((_vm.days),function(day){return _c('div',{staticClass:"calendar-date text-center",class:_vm.getCellClass(day)},[_c('button',{staticClass:"date-item",class:_vm.getButtonClass(day),attrs:{"type":"button","disabled":_vm.isDisabled(day)},on:{"click":function($event){$event.stopPropagation();_vm.isDisabled(day) ? null : _vm.selectDate(day)}}},[_vm._v(_vm._s(day.getDate()))])])}),0)])])],1)}
var datepickervue_type_template_id_a5f44e50_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/datepicker.vue?vue&type=template&id=a5f44e50&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/date-input.vue?vue&type=template&id=68a2bfc6&
var date_inputvue_type_template_id_68a2bfc6_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"date-input"},[_c('div',{staticClass:"input-group input-inline",style:(_vm.computedStyles)},[_c('div',{staticClass:"form-input"},[(_vm.dateString)?_c('div',[_c('span',{staticClass:"chip"},[_vm._v(" "+_vm._s(_vm.dateString)+" "),_c('a',{staticClass:"btn btn-clear",attrs:{"href":"#","aria-label":"Close","role":"button"},on:{"click":function($event){$event.preventDefault();return _vm.$emit('date-clear')}}})])]):_c('input',{staticClass:"form-input",attrs:{"type":"text","autocomplete":"off"},domProps:{"value":_vm.formattedValue},on:{"blur":function($event){return _vm.$emit('dateinput-blur', $event.target.value)}}})]),(_vm.showButton)?_c('button',{staticClass:"btn webfont-icon-only calendarPopper btn-primary",attrs:{"type":"button"},on:{"click":function($event){$event.stopPropagation();return _vm.$emit('toggle-datepicker')}}},[_vm._v("")]):_vm._e()])])}
var date_inputvue_type_template_id_68a2bfc6_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/date-input.vue?vue&type=template&id=68a2bfc6&

// CONCATENATED MODULE: ./vue/util/date-functions.js
const defaultMonthNames = "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" ");
const defaultDayNames = "Sun Mon Tue Wed Thu Fri Sat".split(" ");

/* harmony default export */ var date_functions = ({

    formatDate(date, format, options) {

        if (!date instanceof Date) {
            return "";
        }

        let dayNames = options && options.dayNames ? options.dayNames : defaultDayNames;
        let monthNames = options && options.monthNames ? options.monthNames : defaultMonthNames;

        return format
            .replace(/\bd\b/, date.getDate())
            .replace(/\bdd\b/, ("0" + date.getDate()).slice(-2))
            .replace(/\bm\b/, date.getMonth() + 1)
            .replace(/\bmm\b/, ("0" + (date.getMonth() + 1)).slice(-2))
            .replace(/\bmmm\b/, monthNames[date.getMonth()].trim())
            .replace(/\by\b/, date.getFullYear())
            .replace(/\bw\b/, dayNames[date.getDay()].trim());
    },

    parseDate(dateString, format) {

        let matches, escapedFormat = format.toLowerCase().replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), posMap = [];

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

        if(!result) {
            return false;
        }
        result = new Date(result);
        return new Date(result.getFullYear(), result.getMonth(), result.getDate(), 0, 0, 0);

    },

    isValidTime: function (timeString) {
        let matches = timeString.match(/^\s*([0-9]{1,2}):([0-9]{1,2})(?::([0-9]{1,2}))?\s*$/);
        return (
            matches
            &&
            matches[1] < 24
            &&
            matches[2] < 60
            &&
            (!matches[3] || matches[3] < 60)
        );
    }
});
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/date-input.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var date_inputvue_type_script_lang_js_ = ({

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
                return date_functions.formatDate(this.date, this.outputFormat);
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
            this.dateString = newValue ? date_functions.formatDate(newValue, this.outputFormat) : '';
        }
    }
});

// CONCATENATED MODULE: ./vue/components/date-input.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_date_inputvue_type_script_lang_js_ = (date_inputvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/date-input.vue





/* normalize component */

var date_input_component = normalizeComponent(
  components_date_inputvue_type_script_lang_js_,
  date_inputvue_type_template_id_68a2bfc6_render,
  date_inputvue_type_template_id_68a2bfc6_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var date_input = (date_input_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/datepicker.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ var datepickervue_type_script_lang_js_ = ({
    components: {
        DateInput: date_input
    },

    data() {
        return {
            year: null,
            month: null,
            dateDay: null,
            selectedDate: null,
            expanded: !this.hasInput
        };
    },

    watch: {
        initDate (newValue) {
            this.year = newValue.getFullYear();
            this.month = newValue.getMonth();
            this.dateDay = newValue.getDate();
        },
        pickedDate (newValue) {
            this.selectedDate = new Date(newValue.getFullYear(), newValue.getMonth(), newValue.getDate(), 0, 0, 0);
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
                style: { position: 'relative' }
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
        initDate: {
            type: Date,
            default: () => (new Date())
        },
        pickedDate: {
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
            default: (() => "M D M D F S S".split(" "))
        },
        monthNames: {
            type: Array,
            default: (() => "Jan Feb Mar Apr Mai Jun Jul Aug Sep Okt Nov Dez".split(" "))
        },
        startOfWeekIndex: {
            type: Number,
            default: 1,
            validator: value => !value || value === 1
        },
        hasInput: {
            type: Boolean,
            default: true
        },
        inputFormat: {
            type: String,
            default: 'y-m-d'
        }
    },

    mounted () {
        if(this.hasInput) {
            document.body.addEventListener('click', this.handleDocumentClick);
        }
        this.year = this.initDate.getFullYear();
        this.month = this.initDate.getMonth();
        this.dateDay = this.initDate.getDate();
        if(this.pickedDate) {
            this.selectedDate = new Date(this.pickedDate.getFullYear(), this.pickedDate.getMonth(), this.pickedDate.getDate(), 0, 0, 0);
        }
    },
    beforeDestroy() {
        if(this.hasInput) {
            document.body.removeEventListener('click', this.handleDocumentClick);
        }
    },

    methods: {
        isDisabled(day) {
            return (this.validFrom && this.validFrom > day) || (this.validUntil && this.validUntil < day())
        },
        getCellClass(day) {
            switch(day.getMonth() - this.month) {
                case -1:
                    return 'prev-month';
                case 1:
                    return 'next-month';
                default:
                    return '';
            }
        },
        getButtonClass(day) {
            const classes = [];
            if(this.today.getTime() === day.getTime()) {
                classes.push('date-today');
            }
            if(this.selectedDate && this.selectedDate.getTime() === day.getTime()) {
                classes.push('active');
            }
            return classes;
        },
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
            this.$emit('select', day);
            this.expanded = !this.hasInput;
        },
        toggleDatepicker() {
            this.expanded = !this.expanded;
        },
        handleDocumentClick() {
            this.expanded = false;
        },
        updateDate(dateString) {
            let day = date_functions.parseDate(dateString, this.inputFormat);
            if(day) {
                this.selectedDate = day;
                this.$emit("select", day);
            }
        },
        clearDate() {
            this.selectedDate = null;
            this.$emit('clear');
        }
    }
});

// CONCATENATED MODULE: ./vue/components/datepicker.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_datepickervue_type_script_lang_js_ = (datepickervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/datepicker.vue





/* normalize component */

var datepicker_component = normalizeComponent(
  components_datepickervue_type_script_lang_js_,
  datepickervue_type_template_id_a5f44e50_render,
  datepickervue_type_template_id_a5f44e50_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var datepicker = (datepicker_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/sortable.vue?vue&type=template&id=681dda3c&
var sortablevue_type_template_id_681dda3c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('table',{staticClass:"table table-striped"},[_c('thead',[_c('tr',_vm._l((_vm.columns),function(column){return _c('th',{class:[
                'vx-sortable-header',
                column.cssClass,
                _vm.sortColumn === column ? _vm.sortDir : null,
                column.width
            ],on:{"click":function($event){column.sortable ? _vm.clickSort(column) : null}}},[_vm._t(column.prop + '-header',[_vm._v(" "+_vm._s(column.label)+" ")],{"column":column})],2)}),0)]),_c('tbody',_vm._l((_vm.sortedRows),function(row){return _c('tr',{key:row.key,class:row.cssClass},_vm._l((_vm.columns),function(column){return _c('td',{class:{ 'active': _vm.sortColumn === column }},[_vm._t(column.prop,[_vm._v(_vm._s(row[column.prop]))],{"row":row})],2)}),0)}),0)])}
var sortablevue_type_template_id_681dda3c_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/sortable.vue?vue&type=template&id=681dda3c&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/sortable.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var sortablevue_type_script_lang_js_ = ({
    name: 'sortable',

    props: {
        columns: {
            type: Array,
            required: true,
            validator (val) {
                for(let l = val.length; l--;) {
                    if(val[l].label === undefined || val[l].prop === undefined) {
                        return false;
                    }
                }
                return true;
            }
        },
        rows: {
            type: Array,
            required: true
        },
        sortProp: {
            type: String
        },
        sortDirection: {
            type: String,
            validator (val) {
                return !val || ['asc', 'desc'].indexOf(val) !== -1;
            }
        }
    },

    data() {
        return {
            sortColumn: this.columns.find(item => item.prop === this.sortProp),
            sortDir: this.sortDirection
        };
    },

    computed: {
        sortedRows () {
            if(!this.sortColumn) {
                return this.rows;
            }
            return this.doSort(this.sortColumn, this.sortDir);
        }
    },

    methods: {
        clickSort (column) {
            this.$emit('before-sort');
            if(this.sortColumn === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            }
            else {
                this.sortColumn = column;
            }
            this.$nextTick( () => this.$emit('after-sort') );
        },
        doSort (column, dir) {
            let rows = this.rows;

            if (dir === 'asc' && column.sortAscFunction) {
                rows.sort (column.sortAscFunction);
            }
            else if (dir === 'desc' && column.sortDescFunction) {
                rows.sort (column.sortDescFunction);
            }
            else {
                let prop = column.prop;

                rows.sort((a, b) => {
                    if (a[prop] < b[prop]) {
                        return dir === "asc" ? -1 : 1;
                    }
                    if (a[prop] > b[prop]) {
                        return dir === "asc" ? 1 : -1;
                    }
                    return 0;
                });
            }

            return rows;
        }
    }
});

// CONCATENATED MODULE: ./vue/components/sortable.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_sortablevue_type_script_lang_js_ = (sortablevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/sortable.vue





/* normalize component */

var sortable_component = normalizeComponent(
  components_sortablevue_type_script_lang_js_,
  sortablevue_type_template_id_681dda3c_render,
  sortablevue_type_template_id_681dda3c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var sortable = (sortable_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/simple-tree.vue?vue&type=template&id=36dfc6d9&
var simple_treevue_type_template_id_36dfc6d9_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('ul',{staticClass:"vx-tree"},[_c('simple-tree-branch',{directives:[{name:"bubble",rawName:"v-bubble.branch-selected",modifiers:{"branch-selected":true}}],attrs:{"branch":_vm.branch}})],1)}
var simple_treevue_type_template_id_36dfc6d9_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/simple-tree.vue?vue&type=template&id=36dfc6d9&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/simple-tree-branch.vue?vue&type=template&id=3afe1721&
var simple_tree_branchvue_type_template_id_3afe1721_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('li',{class:{ 'terminates': !_vm.branch.branches || !_vm.branch.branches.length }},[(_vm.branch.branches && _vm.branch.branches.length)?[_c('input',{attrs:{"type":"checkbox","id":'branch-' + _vm.branch.id},domProps:{"checked":_vm.expanded},on:{"click":function($event){_vm.expanded = !_vm.expanded}}}),_c('label',{attrs:{"for":'branch-' + _vm.branch.id}})]:_vm._e(),(_vm.branch.current)?_c('strong',[_vm._v(_vm._s(_vm.branch.label))]):_c('a',{attrs:{"href":_vm.branch.path},on:{"click":function($event){$event.preventDefault();return _vm.$emit('branch-selected', _vm.branch)}}},[_vm._v(_vm._s(_vm.branch.label))]),(_vm.branch.branches && _vm.branch.branches.length)?_c('ul',{directives:[{name:"show",rawName:"v-show",value:(_vm.expanded),expression:"expanded"}]},_vm._l((_vm.branch.branches),function(child){return _c('simple-tree-branch',{directives:[{name:"bubble",rawName:"v-bubble.branch-selected",modifiers:{"branch-selected":true}}],key:child.id,attrs:{"branch":child}})}),1):_vm._e()],2)}
var simple_tree_branchvue_type_template_id_3afe1721_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/simple-tree-branch.vue?vue&type=template&id=3afe1721&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/simple-tree-branch.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var simple_tree_branchvue_type_script_lang_js_ = ({
  name: 'simple-tree-branch',

  data () { return {
    expanded: false
  }},

  props: {
    branch: { type: Object, default: () => { return {} } }
  },

  mounted () {
    if(this.branch.current) {
      let parent = this.$parent;
      while(parent && parent.branch && parent.expanded !== undefined) {
        parent.expanded = true;
        parent = parent.$parent;
      }
    }
  }
});

// CONCATENATED MODULE: ./vue/components/simple-tree-branch.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_simple_tree_branchvue_type_script_lang_js_ = (simple_tree_branchvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/simple-tree-branch.vue





/* normalize component */

var simple_tree_branch_component = normalizeComponent(
  components_simple_tree_branchvue_type_script_lang_js_,
  simple_tree_branchvue_type_template_id_3afe1721_render,
  simple_tree_branchvue_type_template_id_3afe1721_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var simple_tree_branch = (simple_tree_branch_component.exports);
// CONCATENATED MODULE: ./vue/directives.js
// simple directive to enable event bubbling

const Bubble = {
    bind (el, binding, vnode) {
        Object.keys(binding.modifiers).forEach(event => {
            // Bubble events of Vue components
            if (vnode.componentInstance) {
                vnode.componentInstance.$off(event);
                vnode.componentInstance.$on(event, (...args) => {
                    vnode.context.$emit(event, ...args);
                });
                // Bubble events of native DOM elements
            } else {
                el.addEventListener(event, payload => {
                    vnode.context.$emit(event, payload);
                });
            }
        })
    }
};

// focus an element upon insertion

const Focus = { inserted: (el) => el.focus() };



// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/simple-tree.vue?vue&type=script&lang=js&
//
//
//
//
//
//




Vue.directive('bubble', Bubble);

/* harmony default export */ var simple_treevue_type_script_lang_js_ = ({
    name: 'simple-tree',
    props: {
        branch: Object
    },
    components: {
      SimpleTreeBranch: simple_tree_branch
    }
});

// CONCATENATED MODULE: ./vue/components/simple-tree.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_simple_treevue_type_script_lang_js_ = (simple_treevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/simple-tree.vue





/* normalize component */

var simple_tree_component = normalizeComponent(
  components_simple_treevue_type_script_lang_js_,
  simple_treevue_type_template_id_36dfc6d9_render,
  simple_treevue_type_template_id_36dfc6d9_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var simple_tree = (simple_tree_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager.vue?vue&type=template&id=79791056&
var filemanagervue_type_template_id_79791056_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{class:{'dragged-over': _vm.indicateDrag},on:{"drop":function($event){$event.preventDefault();return _vm.uploadFile($event)},"dragover":function($event){$event.preventDefault();_vm.indicateDrag = true},"dragleave":function($event){$event.preventDefault();_vm.indicateDrag = false}}},[_c('div',{staticClass:"vx-button-bar navbar"},[_c('section',{staticClass:"navbar-section"},[_c('span',{staticClass:"btn-group"},_vm._l((_vm.breadcrumbs),function(breadcrumb){return _c('button',{key:breadcrumb.id,staticClass:"btn",class:{'active': breadcrumb.id === _vm.currentFolder.id },on:{"click":function($event){return _vm.readFolder(breadcrumb)}}},[_vm._v(_vm._s(breadcrumb.name))])}),0)]),_c('section',{staticClass:"navbar-section"},[(_vm.uploadInProgress)?[_c('button',{staticClass:"btn btn-link webfont-icon-only tooltip",attrs:{"data-tooltip":"Abbrechen","type":"button"},on:{"click":_vm.cancelUpload}},[_vm._v("")]),_c('label',{staticClass:"d-inline-block mr-2"},[_vm._v(_vm._s(_vm.progress.file))]),_c('progress',{staticClass:"progress",attrs:{"max":_vm.progress.total},domProps:{"value":_vm.progress.loaded}})]:_c('strong',{staticClass:"text-primary d-block col-12 text-center"},[_vm._v("Uploads hierher ziehen")])],2),_c('section',{staticClass:"navbar-section"},[(_vm.showAddFolderInput)?_c('input',{directives:[{name:"focus",rawName:"v-focus"}],ref:"addFolderInput",staticClass:"form-input",on:{"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.addFolder($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }_vm.showAddFolderInput = false}],"blur":function($event){_vm.showAddFolderInput = false}}}):_vm._e(),(!_vm.showAddFolderInput)?_c('button',{staticClass:"btn with-webfont-icon-right btn-primary",attrs:{"type":"button","data-icon":""},on:{"click":function($event){_vm.showAddFolderInput = true}}},[_vm._v("Verzeichnis anlegen")]):_vm._e()])]),_c('sortable',{ref:"sortable",attrs:{"rows":_vm.directoryEntries,"columns":_vm.columns,"sort-prop":_vm.initSort.column,"sort-direction":_vm.initSort.dir},on:{"after-sort":function($event){return _vm.$emit('after-sort', { sortColumn: _vm.$refs.sortable.sortColumn, sortDir: _vm.$refs.sortable.sortDir })}},scopedSlots:_vm._u([{key:"name",fn:function(slotProps){return [(slotProps.row.isFolder)?[(slotProps.row === _vm.toRename)?_c('input',{directives:[{name:"focus",rawName:"v-focus"}],staticClass:"form-input",domProps:{"value":slotProps.row.name},on:{"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.renameFolder($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }_vm.toRename = null}],"blur":function($event){_vm.toRename = null}}}):[_c('a',{attrs:{"href":'#' + slotProps.row.id},on:{"click":function($event){$event.preventDefault();return _vm.readFolder(slotProps.row)}}},[_vm._v(_vm._s(slotProps.row.name))]),_c('button',{staticClass:"btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2",attrs:{"data-tooltip":"Umbenennen"},on:{"click":function($event){_vm.toRename = slotProps.row}}},[_vm._v("")])]]:[(slotProps.row === _vm.toRename)?_c('input',{directives:[{name:"focus",rawName:"v-focus"}],staticClass:"form-input",domProps:{"value":slotProps.row.name},on:{"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.renameFile($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }_vm.toRename = null}],"blur":function($event){_vm.toRename = null}}}):[_c('span',[_vm._v(_vm._s(slotProps.row.name))]),_c('button',{staticClass:"btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2",attrs:{"data-tooltip":"Umbenennen"},on:{"click":function($event){_vm.toRename = slotProps.row}}},[_vm._v("")])]]]}},{key:"size",fn:function(slotProps){return [(!slotProps.row.isFolder)?[_vm._v(_vm._s(_vm._f("formatFilesize")(slotProps.row.size,',')))]:_vm._e()]}},{key:"type",fn:function(slotProps){return [(slotProps.row.image)?_c('img',{attrs:{"src":slotProps.row.src,"alt":""}}):_c('span',[_vm._v(_vm._s(slotProps.row.type))])]}},_vm._l((_vm.$scopedSlots),function(_,name){return {key:name,fn:function(slotData){return [_vm._t(name,null,null,slotData)]}}})],null,true)}),(_vm.showEditForm)?_c('div',{staticClass:"modal active"},[_c('div',{staticClass:"modal-overlay"}),_c('div',{staticClass:"modal-container"},[_c('div',{staticClass:"modal-header"},[_c('a',{staticClass:"btn btn-clear float-right",attrs:{"href":"#close","aria-label":"Close"},on:{"click":function($event){$event.preventDefault();_vm.showEditForm = false}}})]),_c('div',{staticClass:"modal-body"},[_c('file-edit-form',{ref:"editForm",attrs:{"initial-data":_vm.editFormData,"file-info":_vm.editFileInfo,"url":_vm.routes.updateFile},on:{"response-received":function (response) { return _vm.$emit('response-received', response); }}})],1)])]):_vm._e(),(_vm.showFolderTree)?_c('div',{staticClass:"modal active"},[_c('div',{staticClass:"modal-overlay"}),_c('div',{staticClass:"modal-container"},[_c('div',{staticClass:"modal-header"},[_c('a',{staticClass:"btn btn-clear float-right",attrs:{"href":"#close","aria-label":"Close"},on:{"click":function($event){$event.preventDefault();_vm.showFolderTree = false}}}),_c('div',{staticClass:"modal-title h5"},[_vm._v("Zielordner wählen…")])]),_c('div',{staticClass:"modal-body"},[_c('simple-tree',{attrs:{"branch":_vm.root},on:{"branch-selected":_vm.moveToFolder}})],1)])]):_vm._e()],1)}
var filemanagervue_type_template_id_79791056_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/filemanager.vue?vue&type=template&id=79791056&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/file-edit-form.vue?vue&type=template&id=4b8d70c8&
var file_edit_formvue_type_template_id_4b8d70c8_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{attrs:{"action":"/","id":"events-edit-form"},on:{"submit":function($event){$event.preventDefault();}}},[_c('div',{staticClass:"columns"},[_c('div',{staticClass:"column"},[(_vm.fileInfo.thumb)?_c('img',{staticClass:"img-responsive",attrs:{"src":_vm.fileInfo.thumb}}):_vm._e()]),_c('div',{staticClass:"column"},[_c('table',{staticClass:"table"},[_c('tr',[_c('td',[_vm._v("Typ")]),_c('td',[_vm._v(_vm._s(_vm.fileInfo.mimetype))])]),(_vm.fileInfo.cache)?_c('tr',[_c('td',[_vm._v("Cache")]),_c('td',[_vm._v(_vm._s(_vm.fileInfo.cache.count)+" Files, "+_vm._s(_vm._f("formatFilesize")(_vm.fileInfo.cache.totalSize,',')))])]):_vm._e(),_c('tr',[_c('td',[_vm._v("Link")]),_c('td',[_c('a',{attrs:{"href":'/' + _vm.fileInfo.path,"target":"_blank"}},[_vm._v(_vm._s(_vm.fileInfo.name))])])])])])]),_c('div',{staticClass:"divider",attrs:{"data-content":"Metadaten der Datei"}}),_c('div',{staticClass:"form-group"},[_c('label',{attrs:{"for":"title_input"}},[_vm._v("Titel")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.title),expression:"form.title"}],staticClass:"form-input",class:{'is-error': _vm.errors.title},attrs:{"id":"title_input","autocomplete":"off"},domProps:{"value":(_vm.form.title)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "title", $event.target.value)}}})]),_c('div',{staticClass:"form-group"},[_c('label',{attrs:{"for":"subtitle_input"}},[_vm._v("Untertitel")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.subtitle),expression:"form.subtitle"}],staticClass:"form-input",class:{'is-error': _vm.errors.subtitle},attrs:{"id":"subtitle_input","autocomplete":"off"},domProps:{"value":(_vm.form.subtitle)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "subtitle", $event.target.value)}}})]),_c('div',{staticClass:"form-group"},[_c('label',{attrs:{"for":"description_input"}},[_vm._v("Beschreibung")]),_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.description),expression:"form.description"}],staticClass:"form-input",class:{'is-error': _vm.errors.description},attrs:{"rows":"2","id":"description_input"},domProps:{"value":(_vm.form.description)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "description", $event.target.value)}}})]),_c('div',{staticClass:"divider",attrs:{"data-content":"Erweiterte Einstellungen"}}),_c('div',{staticClass:"divider"}),_c('div',{staticClass:"form-group"},[_c('label',{attrs:{"for":"customsort_input"}},[_vm._v("Untertitel")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.customsort),expression:"form.customsort"}],staticClass:"form-input col-4",class:{'is-error': _vm.errors.customsort},attrs:{"id":"customsort_input","autocomplete":"off"},domProps:{"value":(_vm.form.customsort)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "customsort", $event.target.value)}}})]),_c('div',{staticClass:"form-group"},[_c('button',{staticClass:"btn btn-success col-12",class:{'loading': _vm.loading},attrs:{"type":"button","disabled":_vm.loading},on:{"click":_vm.submit}},[_vm._v("Änderungen speichern")])])])}
var file_edit_formvue_type_template_id_4b8d70c8_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue?vue&type=template&id=4b8d70c8&

// CONCATENATED MODULE: ./vue/util/simple-fetch.js
async function SimpleFetch(url, method = 'GET', headers = {}, payload = null) {

    if(!headers['X-CSRF-Token'] && document.querySelector('meta[name="csrf-token"]')) {
        headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    }

    const response = await fetch(
        url,
        {
            method: method.toUpperCase(),
            body: payload,
            headers: headers
        }
    );

    return await response.json();
}

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/file-edit-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var file_edit_formvue_type_script_lang_js_ = ({
    props: {
        initialData: { type: Object, default: () => { return {} } },
        fileInfo: { type: Object, default: () => { return {} } },
        url: { type: String, default: "" }
    },
    data() {
        return {
            form: {},
            response: {},
            loading: false
        }
    },
    computed: {
        errors () {
            return this.response ? (this.response.errors || {}) : {};
        },
        message () {
            return this.response ? this.response.message : "";
        }
    },
    watch: {
        initialData (newValue) {
            this.form = Object.assign({}, this.form, newValue);
        }
    },
    methods: {
        async submit() {
            this.loading = true;

            /* avoid strings "null" with null values */

            let formData = {};

            Object.keys(this.form).forEach(key => { if(this.form[key] !== null) { formData[key] = this.form[key]; }});

            this.response = await SimpleFetch(this.url, 'POST', {}, JSON.stringify(formData));
            this.$emit('response-received', this.response);
            this.loading = false;
        }
    },
    filters: {
        formatFilesize(size, sep) {
            let suffixes = ['B', 'kB', 'MB', 'GB'], ndx = Math.floor(Math.floor(Math.log(size) / Math.log(1000)));
            return (size / Math.pow(1000, ndx)).toFixed(ndx ? 2: 0).toString().replace('.', sep || '.') + suffixes[ndx];
        }
    }
});

// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_file_edit_formvue_type_script_lang_js_ = (file_edit_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/file-edit-form.vue





/* normalize component */

var file_edit_form_component = normalizeComponent(
  forms_file_edit_formvue_type_script_lang_js_,
  file_edit_formvue_type_template_id_4b8d70c8_render,
  file_edit_formvue_type_template_id_4b8d70c8_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var file_edit_form = (file_edit_form_component.exports);
// CONCATENATED MODULE: ./vue/util/promised-xhr.js
function PromisedXhr(url, method = 'GET', headers = {}, payload = null, timeout = null, progressCallback = null, cancelToken = null) {

    if(!headers['X-CSRF-Token'] && document.querySelector('meta[name="csrf-token"]')) {
        headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    }
    headers['Content-type'] = headers['Content-type'] || 'application/x-www-form-urlencoded';

    let xhr = new XMLHttpRequest();

    let xhrPromise = new Promise(function(resolve, reject) {
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(JSON.parse(xhr.responseText));
                } else {
                    reject({
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText
                    });
                }
            }
        };

        xhr.upload.onprogress = progressCallback || null;

        if(cancelToken) {
            cancelToken.cancel = () =>  { xhr.abort(); reject({ status: 499, statusText: 'Request cancelled.' }); };
        }

        xhr.open(method, url, true);
        xhr.responseType = 'text'; // currently no JSON support in IE/Edge
        Object.keys(headers).forEach(key => xhr.setRequestHeader(key, headers[key]));

        if (timeout) {
            xhr.timeout = timeout;
            xhr.ontimeout = (e) => {reject({ status: 408, statusText: 'Request timeout.' });};
        }

        xhr.send(payload);
    });

    xhrPromise.cancel = () => xhr.abort();

    return xhrPromise;
}

// CONCATENATED MODULE: ./vue/filters.js

function formatFilesize (size, sep) {
    let i = Math.floor(Math.floor(Math.log(size) / Math.log(1000)));
    return (size / Math.pow(1000, i)).toFixed(i ? 2 : 0).toString().replace('.', sep || '.') + ['B', 'kB', 'MB', 'GB'][i];
}


// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/filemanager.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//









/* harmony default export */ var filemanagervue_type_script_lang_js_ = ({
    components: {
        'sortable': sortable, 'simple-tree': simple_tree, 'file-edit-form': file_edit_form
    },

    data () {
        return {
            root: {},
            currentFolder: {},
            files: [],
            folders: [],
            breadcrumbs: [],
            toRename: null,
            toMove: null,
            showAddFolderInput: false,
            showEditForm: false,
            showFolderTree: false,
            indicateDrag: false,
            uploads: [],
            uploadInProgress: false,
            cancelUploadToken: {},
            progress: { total: null, loaded: null, file: null },
            editFormData: {},
            editFileInfo: {}
        }
    },

    computed: {
        directoryEntries() {
            let folders = this.folders;
            let files = this.files;
            folders.forEach(item => {
                item.isFolder = true;
                item.key = 'd' + item.id
            });
            files.forEach(item => item.key = item.id);
            return [...folders, ...files];
        }
    },

    props: {
        routes: { type: Object, required: true },
        columns: { type: Array, required: true },
        initSort: { type: Object }
    },

    async created () {
        let response = await SimpleFetch(this.routes.init);

        this.breadcrumbs = response.breadcrumbs || [];
        this.files = response.files || [];
        this.folders = response.folders || [];
        this.currentFolder = response.currentFolder;
    },

    methods: {
        async readFolder (row) {
            let response = await SimpleFetch(this.routes.readFolder + '?id=' + row.id);

            if(response.success) {
                this.files = response.files || [];
                this.folders = response.folders || [];
                this.currentFolder = row;
                if(!this.breadcrumbs) {
                    return;
                }
                if(
                    response.breadcrumbs.length >= this.breadcrumbs.length ||
                    this.breadcrumbs.map(item => item.id).join().indexOf(response.breadcrumbs.map(item => item.id).join()) !== 0
                ) {
                    this.breadcrumbs = response.breadcrumbs;
                }
            }
        },
        async editFile (row) {
            this.showEditForm = true;
            let response = await SimpleFetch(this.routes.getFile + '?id=' + row.id);
            this.editFormData = response.form || {};
            this.editFileInfo = response.fileInfo || {};
            this.editFormData.id = row.id;
        },
        async delFile (row) {
            if(window.confirm("Datei '" + row.name + "' wirklich löschen?")) {
                let response = await SimpleFetch(this.routes.delFile + '?id=' + row.id, 'DELETE');
                if(response.success) {
                    this.files.splice(this.files.findIndex(item => row === item), 1);
                }
            }
        },
        async renameFile (event) {
            let name = event.target.value.trim();
            if(name && this.toRename) {
                let response = await SimpleFetch(this.routes.renameFile, 'POST', {}, JSON.stringify({name: name, id: this.toRename.id }));
                if(response.success) {
                    this.toRename.name = response.name || name;
                    this.toRename = null;
                }
            }
        },
        async renameFolder (event) {
            let name = event.target.value.trim();
            if(name && this.toRename) {
                let response = await SimpleFetch(this.routes.renameFolder, 'POST', {}, JSON.stringify({name: name, id: this.toRename.id }));
                if(response.success) {
                    let ndx = this.breadcrumbs.findIndex(item => item.id === this.toRename.id);
                    if (ndx !== -1) {
                        this.breadcrumbs[ndx].name = response.name;
                    }
                    this.toRename.name = response.name || name;
                    this.toRename = null;
                }
            }
        },
        async delFolder (row) {
            if(window.confirm("Ordner und Inhalt von '" + row.name + "' wirklich löschen?")) {
                let response = await SimpleFetch(this.routes.delFolder + '?id=' + row.id, 'DELETE');
                if(response.success) {
                    this.folders.splice(this.folders.findIndex(item => row === item), 1);
                    let ndx = this.breadcrumbs.findIndex(item => item.id === row.id);
                    if (ndx !== -1) {
                        this.breadcrumbs.splice(ndx);
                    }
                }
            }
        },
        async addFolder () {
            let name = this.$refs.addFolderInput.value.trim();
            if(name) {
                let response = await SimpleFetch(this.routes.addFolder, 'POST', {}, JSON.stringify({name: name, parent: this.currentFolder.id }));
                if(response.success) {
                    this.showAddFolderInput = false;
                }
                if(response.folder) {
                    this.folders.push(response.folder);
                }
            }
        },
        async getFolderTree (row) {
            this.toMove = row;
            let response = await SimpleFetch(this.routes.getFoldersTree + '?id=' + this.currentFolder.id);
            this.showFolderTree = true;
            this.root = response;
        },
        async moveToFolder (folder) {
            if(this.toMove) {
                let response = await SimpleFetch(this.routes.moveFile, 'POST', {}, JSON.stringify({
                    id: this.toMove.id,
                    folderId: folder.id
                }));
                if (response.success) {
                    this.files.splice(this.files.findIndex(item => this.toMove === item), 1);
                    this.toMove = null;
                    this.showFolderTree = false;
                }
                else {
                    this.$emit('response-received', response);
                }
            }
        },
        uploadFile (event) {
            this.indicateDrag = false;
            let droppedFiles = event.dataTransfer.files;

            if (!droppedFiles) {
                return;
            }
            [...droppedFiles].forEach(f => this.uploads.push(f));

            if(!this.uploadInProgress) {
                this.uploadInProgress = true;
                this.handleUploads();
            }
        },
        async handleUploads () {
            let file = null, response = null;
            while((file = this.uploads.shift()) !== undefined) {
                this.progress.file = file.name;
                try {
                    response = await PromisedXhr(
                        this.routes.uploadFile + '?id=' + this.currentFolder.id,
                        'POST',
                        {
                            'Content-type': file.type || 'application/octet-stream',
                            'X-File-Name': file.name.replace(/[^\x00-\x7F]/g, c => encodeURIComponent(c)),
                            'X-File-Size': file.size,
                            'X-File-Type': file.type
                        },
                        file,
                        null,
                        e => {
                            this.progress.total = e.total;
                            this.progress.loaded = e.loaded;
                        },
                        this.cancelUploadToken
                    );
                    this.files = response.files || [];
                } catch(err) {
                    this.uploads = [];
                    this.uploadInProgress = false;
                    return;
                }

                if(!response.success) {
                    this.$emit('response-received', response);
                    this.uploads = [];
                    this.uploadInProgress = false;
                    return;
                }
            }
            this.$emit('response-received', { success: true, message: response.message || 'File upload successful' });
            this.uploadInProgress = false;
        },
        cancelUpload () {
            if(this.cancelUploadToken.cancel) {
                this.cancelUploadToken.cancel();
                this.cancelUploadToken = {};
            }
        }
    },

    directives: {
        focus: Focus
    },

    filters: {
        formatFilesize: formatFilesize
    }
});

// CONCATENATED MODULE: ./vue/components/filemanager.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_filemanagervue_type_script_lang_js_ = (filemanagervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/filemanager.vue





/* normalize component */

var filemanager_component = normalizeComponent(
  components_filemanagervue_type_script_lang_js_,
  filemanagervue_type_template_id_79791056_render,
  filemanagervue_type_template_id_79791056_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var filemanager = (filemanager_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/tab.vue?vue&type=template&id=c0b3a9ea&
var tabvue_type_template_id_c0b3a9ea_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[(_vm.items.length)?_c('ul',{staticClass:"tab",class:{'tab-block': _vm.block}},[_vm._l((_vm.items),function(item,ndx){return _c('li',{key:ndx,staticClass:"tab-item",class:{ active: _vm.activeIndex === ndx }},[_c('z-link',{class:{ 'disabled': item.disabled },attrs:{"badge":item.badge,"name":item.name},on:{"click":function($event){return _vm.itemOnClick(item)}}})],1)}),(_vm.hasActionSlot)?_c('li',{staticClass:"tab-item tab-action"},[_vm._t("action")],2):_vm._e()],2):_vm._e()])}
var tabvue_type_template_id_c0b3a9ea_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/tab.vue?vue&type=template&id=c0b3a9ea&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/tab.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var tabvue_type_script_lang_js_ = ({
  name: 'Tab',

  props: {
    items: {
      type: Array,
      default: []
    },
    activeIndex: {
      type: Number,
      default: 0
    },
    block: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      activeTab: {},
    };
  },

  computed: {
    hasActionSlot() {
      return !!this.$slots.action;
    }
  },

  created() {
    this.activeTab = this.items[this.activeIndex] || {};
  },
  watch: {
    activeIndex(newVal) {
      this.activeTab = this.items[newVal] || {};
    },
  },
  methods: {
    itemOnClick(item) {
      if(!item.disabled) {
        this.activeTab = item;
        this.$emit('update:activeIndex', this.items.indexOf(item));
      }
    }
  }
});

// CONCATENATED MODULE: ./vue/components/tab.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_tabvue_type_script_lang_js_ = (tabvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/tab.vue





/* normalize component */

var tab_component = normalizeComponent(
  components_tabvue_type_script_lang_js_,
  tabvue_type_template_id_c0b3a9ea_render,
  tabvue_type_template_id_c0b3a9ea_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var tab = (tab_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/zutre/pagination.vue?vue&type=template&id=ec2adb82&
var paginationvue_type_template_id_ec2adb82_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('ul',{staticClass:"pagination"},[(_vm.showNavButtons)?_c('li',{staticClass:"page-item",class:{disabled: !_vm.hasPrev}},[_c('z-link',{attrs:{"tabindex":"-1"},on:{"click":function () { return _vm.prevPage(); }}},[_vm._v(_vm._s(_vm.prevText))])],1):_vm._e(),_vm._l((_vm.pagesToShow),function(page,idx){return _c('li',{key:idx,staticClass:"page-item",class:{active: _vm.currentPage === page}},[(page !== 'dots')?_c('z-link',{on:{"click":function () { return _vm.pageClick(page); }}},[_vm._v(_vm._s(page))]):_c('span',[_vm._v("...")])],1)}),(_vm.showNavButtons)?_c('li',{staticClass:"page-item",class:{disabled: !_vm.hasNext}},[_c('z-link',{attrs:{"tabindex":"-1"},on:{"click":function () { return _vm.nextPage(); }}},[_vm._v(_vm._s(_vm.nextText))])],1):_vm._e()],2)])}
var paginationvue_type_template_id_ec2adb82_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/zutre/pagination.vue?vue&type=template&id=ec2adb82&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/zutre/pagination.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

const props = {
  prevText: {
    type: String,
    default: () => 'Previous',
  },
  nextText: {
    type: String,
    default: () => 'Next',
  },
  items: Array,
  total: {
    type: Number,
    default: () => 0,
  },
  page: {
    type: Number,
    default: () => 1,
  },
  perPage: {
    type: Number,
    default: () => 20,
  },
  showNavButtons: {
    type: Boolean,
    default: () => true,
  },
  showAllPages: {
    type: Boolean,
    default: () => false,
  },
  onPageChange: Function,
};

const data = function() {
 return {
   currentPage: 1,
   maxPage: 0,
   showPerPage: 20,
   dataItems: undefined,
 };
};

/* harmony default export */ var paginationvue_type_script_lang_js_ = ({
  name: 'Pagination',
  props,
  data,
  created() {
    this.currentPage = this.page;
    this.totalResults = (typeof this.items !== 'undefined') ? this.items.length : this.total;
    this.showPerPage = this.perPage;

    if (typeof this.items !== 'undefined') {
      this.dataItems = this.items;
    }

    this.countMaxPage();

    if (typeof this.onPageChange === 'function') {
      this.onPageChange.apply(null, [this.currentPage, this.dataResults, this.maxPage]);
    }
  },
  methods: {
    pageClick(page) {
      this.currentPage = page;
      this.$emit('update:page', page);

      if (typeof this.onPageChange === 'function') {
        this.onPageChange.apply(null, [page, this.dataResults, this.maxPage]);
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.pageClick(this.currentPage - 1);
      }
    },
    nextPage() {
      if (this.currentPage < this.maxPage) {
        this.pageClick(this.currentPage + 1);
      }
    },
    countMaxPage() {
      this.maxPage = Math.ceil(this.totalResults / this.showPerPage);
    }
  },
  watch: {
    page(val) {
      if (val < 1) {
        val = 1;
      } else if (val > this.maxPage) {
        val = this.maxPage;
      }
      
      this.currentPage = val;

      if (typeof this.onPageChange === 'function') {
        this.onPageChange.apply(null, [this.currentPage, this.dataResults, this.maxPage]);
      }
    },
    total(val) {
      if (val < 0) {
        val = 0;
      }

      this.totalResults = val;

      this.countMaxPage();
      // if total number of items has changed, go to page 1
      this.pageClick(1);
    },
    perPage(val) {
      if (val < 1) {
        val = 20;
      }
      this.showPerPage = val;
      
      this.countMaxPage();
      this.pageClick(1);
    },
    items(val) {
      this.dataItems = val;

      this.totalResults = this.dataItems.length;
      this.$emit('update:total', this.totalResults);

      this.countMaxPage();
      this.pageClick(1);
    }
  },
  computed: {
    hasPrev() {
      return this.currentPage > 1;
    },
    hasNext() {
      return this.currentPage < this.maxPage;
    },
    dataResults() {
      if (typeof this.dataItems !== 'undefined' && this.dataItems.length > 0) {
        let start = (this.currentPage - 1) * this.showPerPage;
        let end = this.currentPage * this.showPerPage;

        return this.dataItems.slice(start, end);
      }

      return null;
    },
    pagesToShow() {
      let pages = [1];

      if (this.showAllPages === true || this.maxPage <= 7) {
        var i = 2;
        for (; i <= this.maxPage; i++) {
          pages.push(i);
        }
        return pages;
      }

      if (this.currentPage >= this.maxPage) {
        pages.push('dots');
        pages.push(this.currentPage - 2);
        pages.push(this.currentPage - 1);
      } else if (this.currentPage - 1 && this.currentPage - 1 > 1) {
        if (this.currentPage - 1 > 2) {
          pages.push('dots');
        }

        pages.push(this.currentPage - 1);
      }

      
      if (this.currentPage > 1) {
        pages.push(this.currentPage);
      }

      if (this.currentPage + 1 < this.maxPage) {
        pages.push(this.currentPage + 1);

        if (this.currentPage <= 1) {
          pages.push(this.currentPage + 2);
        }

        if (this.currentPage + 2 < this.maxPage) {
          pages.push('dots');
        }
      }

      if (this.currentPage < this.maxPage) {
        pages.push(this.maxPage);
      } 

      return pages;
    }
  },
});

// CONCATENATED MODULE: ./vue/components/zutre/pagination.vue?vue&type=script&lang=js&
 /* harmony default export */ var zutre_paginationvue_type_script_lang_js_ = (paginationvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/zutre/pagination.vue





/* normalize component */

var pagination_component = normalizeComponent(
  zutre_paginationvue_type_script_lang_js_,
  paginationvue_type_template_id_ec2adb82_render,
  paginationvue_type_template_id_ec2adb82_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var pagination = (pagination_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/zutre/link.vue?vue&type=template&id=0dbe89af&
var linkvue_type_template_id_0dbe89af_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (!_vm.hasLink)?_c('a',{class:_vm.linkClass,attrs:{"href":_vm.linkHref,"data-badge":_vm.badge},on:{"click":function($event){return _vm.$emit('click')}}},[(_vm.hasDefaultSlot)?[(_vm.hasIcon)?_c('z-icon',{attrs:{"name":_vm.icon}}):_vm._e(),_vm._v(" "),_vm._t("default")]:(!_vm.hasDefaultSlot)?[(_vm.hasIcon)?_c('z-icon',{attrs:{"name":_vm.icon}}):_vm._e(),_vm._v(" "+_vm._s(_vm.linkName))]:_vm._e()],2):(!_vm.hasHref && _vm.hasLink)?_c('router-link',{class:_vm.linkClass,attrs:{"to":_vm.linkRouter,"active-class":_vm.activeClass,"exact":_vm.exact,"data-badge":_vm.badge},on:{"click":function($event){return _vm.$emit('click')}}},[(_vm.hasDefaultSlot)?[(_vm.hasIcon)?_c('z-icon',{attrs:{"name":_vm.icon}}):_vm._e(),_vm._v(" "),_vm._t("default")]:(!_vm.hasDefaultSlot)?[(_vm.hasIcon)?_c('z-icon',{attrs:{"name":_vm.icon}}):_vm._e(),_vm._v(" "+_vm._s(_vm.linkName))]:_vm._e()],2):_vm._e()}
var linkvue_type_template_id_0dbe89af_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/zutre/link.vue?vue&type=template&id=0dbe89af&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/zutre/link.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/**
 * ZLink
 *
 * @author Maciej Lisowski <maciej.lisowski.elk@gmail.com>
 * @prop {String} href
 * @prop {Object} link
 * @prop {String} name
 * @prop {String} icon
 * @prop {String} activeClass
 * @prop {Boolean} active
 * @prop {Boolean} exact
 */
/* harmony default export */ var linkvue_type_script_lang_js_ = ({
  name: 'Link',
  props: {
    href: {
      type: String
    },
    link: {
      type: Object
    },
    name: String,
    icon: String,
    activeClass: {
      type: String,
      default: () => 'active'
    },
    active: {
      type: Boolean,
      default: () => false
    },
    exact: {
      type: Boolean,
      default: () => false
    },
    badge: String
  },
  computed: {
    linkClass: function() {
      let css = { 'menu-item': true, badge: false };

      if (this.active === true && typeof this.activeClass === 'string') {
        css[this.activeClass] = true
      }

      if (typeof this.badge !== 'undefined') {
        css.badge = true;
      }

      return css
    },
    hasIcon() {
      return (typeof this.icon !== 'undefined') ? true : false;
    },
    hasHref() {
      return (typeof this.href !== 'undefined') ? true : false;
    },
    hasLink() {
      return (typeof this.link !== 'undefined') ? true : false;
    },
    hasDefaultSlot() {
      return !!this.$slots.default;
    },
    linkName() {
      return this.name;
    },
    linkHref() {
      return this.href;
    },
    linkRouter() {
      return this.link;
    }
  }
});

// CONCATENATED MODULE: ./vue/components/zutre/link.vue?vue&type=script&lang=js&
 /* harmony default export */ var zutre_linkvue_type_script_lang_js_ = (linkvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/zutre/link.vue





/* normalize component */

var link_component = normalizeComponent(
  zutre_linkvue_type_script_lang_js_,
  linkvue_type_template_id_0dbe89af_render,
  linkvue_type_template_id_0dbe89af_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var zutre_link = (link_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/profile-form.vue?vue&type=template&id=7a5c2626&
var profile_formvue_type_template_id_7a5c2626_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{staticClass:"form-horizontal",attrs:{"action":"/"},on:{"submit":function($event){$event.preventDefault();}}},[_c('div',{staticClass:"form-sect"},[_c('div',{staticClass:"form-group"},[_vm._m(0),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.username),expression:"form.username"}],staticClass:"form-input",attrs:{"name":"username","maxlength":"128","autocomplete":"off","id":"username_input","type":"text"},domProps:{"value":(_vm.form.username)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "username", $event.target.value)}}}),(_vm.errors.username)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.username))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_vm._m(1),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.email),expression:"form.email"}],staticClass:"form-input",attrs:{"name":"email","id":"email_input","autocomplete":"off","maxlength":"128","type":"text"},domProps:{"value":(_vm.form.email)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "email", $event.target.value)}}}),(_vm.errors.email)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.email))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_vm._m(2),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.name),expression:"form.name"}],staticClass:"form-input",attrs:{"name":"name","id":"name_input","autocomplete":"off","maxlength":"128","type":"text"},domProps:{"value":(_vm.form.name)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "name", $event.target.value)}}}),(_vm.errors.name)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.name))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label col-3",attrs:{"for":"pwd_input"}},[_vm._v("Neues Passwort")]),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.new_PWD),expression:"form.new_PWD"}],staticClass:"form-input",attrs:{"name":"new_PWD","id":"pwd_input","autocomplete":"off","maxlength":"128","type":"password"},domProps:{"value":(_vm.form.new_PWD)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "new_PWD", $event.target.value)}}}),(_vm.errors.new_PWD)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.new_PWD))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label col-3",attrs:{"for":"pwd2_input"}},[_vm._v("Passwort wiederholen")]),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.new_PWD_verify),expression:"form.new_PWD_verify"}],staticClass:"form-input",attrs:{"name":"new_PWD_verify","id":"pwd2_input","autocomplete":"off","maxlength":"128","type":"password"},domProps:{"value":(_vm.form.new_PWD_verify)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "new_PWD_verify", $event.target.value)}}}),(_vm.errors.new_PWD_verify)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.new_PWD_verify))]):_vm._e()])])]),(_vm.notifications.length)?[_c('div',{staticClass:"divider text-center",attrs:{"data-content":"Benachrichtigungen"}}),_c('div',{staticClass:"form-sect off-3"},_vm._l((_vm.notifications),function(notification){return _c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-switch"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.notifications),expression:"form.notifications"}],attrs:{"name":"notification[]","type":"checkbox"},domProps:{"value":notification.alias,"checked":Array.isArray(_vm.form.notifications)?_vm._i(_vm.form.notifications,notification.alias)>-1:(_vm.form.notifications)},on:{"change":function($event){var $$a=_vm.form.notifications,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=notification.alias,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.form, "notifications", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.form, "notifications", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.form, "notifications", $$c)}}}}),_c('i',{staticClass:"form-icon"}),_vm._v(_vm._s(notification.label))])])}),0)]:_vm._e(),_c('div',{staticClass:"divider"}),_c('div',{staticClass:"form-base"},[_c('div',{staticClass:"form-group off-3"},[_c('button',{staticClass:"btn btn-success",class:{'loading': _vm.loading},attrs:{"name":"submit_profile","type":"button","disabled":_vm.loading},on:{"click":_vm.submit}},[_vm._v("Änderungen speichern")])])])],2)}
var profile_formvue_type_template_id_7a5c2626_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"form-label col-3",attrs:{"for":"username_input"}},[_c('strong',[_vm._v("Username*")])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"form-label col-3",attrs:{"for":"email_input"}},[_c('strong',[_vm._v("Email*")])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"form-label col-3",attrs:{"for":"name_input"}},[_c('strong',[_vm._v("Name*")])])}]


// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue?vue&type=template&id=7a5c2626&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/profile-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ var profile_formvue_type_script_lang_js_ = ({

    props: {
        url: { type: String, required: true },
        initialData: { type: Object, default: () => { return {} } },
        notifications: { type: Array }
    },

    data() {
        return {
            form: {},
            response: {},
            loading: false
        }
    },

    computed: {
        errors () {
            return this.response ? (this.response.errors || {}) : {};
        },
        message () {
            return this.response ? this.response.message : "";
        }
    },

    watch: {
        initialData (newValue) {
            this.form = newValue;
        }
    },

    methods: {
        async submit() {
            this.loading = true;
            this.$emit("request-sent");
            this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
            this.loading = false;
            this.$emit("response-received");
        }
    }
});

// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_profile_formvue_type_script_lang_js_ = (profile_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/profile-form.vue





/* normalize component */

var profile_form_component = normalizeComponent(
  forms_profile_formvue_type_script_lang_js_,
  profile_formvue_type_template_id_7a5c2626_render,
  profile_formvue_type_template_id_7a5c2626_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var profile_form = (profile_form_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/user-form.vue?vue&type=template&id=742325b2&
var user_formvue_type_template_id_742325b2_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{staticClass:"form-horizontal",attrs:{"action":"/"},on:{"submit":function($event){$event.preventDefault();}}},[_c('div',{staticClass:"form-sect"},[_c('div',{staticClass:"form-group"},[_vm._m(0),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.username),expression:"form.username"}],staticClass:"form-input",attrs:{"name":"username","id":"username_input","autocomplete":"off","maxlength":"128","type":"text"},domProps:{"value":(_vm.form.username)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "username", $event.target.value)}}}),(_vm.errors.username)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.username))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_vm._m(1),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.email),expression:"form.email"}],staticClass:"form-input",attrs:{"name":"email","id":"email_input","autocomplete":"off","maxlength":"128","type":"text"},domProps:{"value":(_vm.form.email)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "email", $event.target.value)}}}),(_vm.errors.email)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.email))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_vm._m(2),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.name),expression:"form.name"}],staticClass:"form-input",attrs:{"name":"name","id":"name_input","autocomplete":"off","maxlength":"128","type":"text"},domProps:{"value":(_vm.form.name)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "name", $event.target.value)}}}),(_vm.errors.name)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.name))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_vm._m(3),_c('div',{staticClass:"col-9"},[_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.admingroupsid),expression:"form.admingroupsid"}],staticClass:"form-select",attrs:{"name":"admingroupsid","id":"admingroupsID_select"},on:{"change":function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.form, "admingroupsid", $event.target.multiple ? $$selectedVal : $$selectedVal[0])}}},_vm._l((_vm.options.admingroups),function(option){return _c('option',{domProps:{"value":option.admingroupsid}},[_vm._v(" "+_vm._s(option.name)+" ")])}),0),(_vm.errors.admingroupsid)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.admingroupsid))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label col-3",attrs:{"for":"pwd_input"}},[_vm._v("Neues Passwort")]),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.new_PWD),expression:"form.new_PWD"}],staticClass:"form-input",attrs:{"name":"new_PWD","id":"pwd_input","autocomplete":"off","maxlength":"128","type":"password"},domProps:{"value":(_vm.form.new_PWD)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "new_PWD", $event.target.value)}}}),(_vm.errors.new_PWD)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.new_PWD))]):_vm._e()])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"form-label col-3",attrs:{"for":"pwd2_input"}},[_vm._v("Passwort wiederholen")]),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.new_PWD_verify),expression:"form.new_PWD_verify"}],staticClass:"form-input",attrs:{"name":"new_PWD_verify","id":"pwd2_input","autocomplete":"off","maxlength":"128","type":"password"},domProps:{"value":(_vm.form.new_PWD_verify)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "new_PWD_verify", $event.target.value)}}}),(_vm.errors.new_PWD_verify)?_c('p',{staticClass:"form-input-hint vx-error-box error"},[_vm._v(_vm._s(_vm.errors.new_PWD_verify))]):_vm._e()])])]),_c('div',{staticClass:"divider"}),_c('div',{staticClass:"form-base"},[_c('div',{staticClass:"form-group off-3"},[_c('button',{staticClass:"btn btn-success",class:{'loading': _vm.loading},attrs:{"name":"submit_user","value":"","type":"button","disabled":_vm.loading},on:{"click":_vm.submit}},[_vm._v(_vm._s(_vm.form.id ? 'Daten übernehmen' : 'User anlegen'))])])])])}
var user_formvue_type_template_id_742325b2_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"form-label col-3",attrs:{"for":"username_input"}},[_c('strong',[_vm._v("Username*")])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"form-label col-3",attrs:{"for":"email_input"}},[_c('strong',[_vm._v("Email*")])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"form-label col-3",attrs:{"for":"name_input"}},[_c('strong',[_vm._v("Name*")])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"form-label col-3",attrs:{"for":"admingroupsid_select"}},[_c('strong',[_vm._v("Gruppe*")])])}]


// CONCATENATED MODULE: ./vue/components/forms/user-form.vue?vue&type=template&id=742325b2&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/user-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ var user_formvue_type_script_lang_js_ = ({

    props: {
        url: { type: String, required: true },
        initialData: { type: Object, default: () => { return {} } },
        options: { type: Object }
    },

    data: function() {
        return {
            form: {},
            response: {},
            loading: false
        }
    },

    computed: {
        errors () {
            return this.response ? (this.response.errors || {}) : {};
        },
        message () {
            return this.response ? this.response.message : "";
        }
    },

    watch: {
        initialData (newValue) {
            this.form = newValue;
        }
    },

    methods: {
        async submit() {
            this.loading = true;
            this.$emit("request-sent");
            this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
            this.loading = false;
            this.$emit("response-received");
        }
    }
});

// CONCATENATED MODULE: ./vue/components/forms/user-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_user_formvue_type_script_lang_js_ = (user_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/user-form.vue





/* normalize component */

var user_form_component = normalizeComponent(
  forms_user_formvue_type_script_lang_js_,
  user_formvue_type_template_id_742325b2_render,
  user_formvue_type_template_id_742325b2_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var user_form = (user_form_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"85014cd0-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/article-form.vue?vue&type=template&id=0f301980&
var article_formvue_type_template_id_0f301980_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{staticClass:"form-horizontal",attrs:{"action":"/"},on:{"submit":function($event){$event.preventDefault();}}},[_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",class:{ 'text-error': _vm.errors.article_date },attrs:{"for":"article_date_input"}},[_vm._v("Artikeldatum")]),_c('div',{staticClass:"col-3"},[_c('div',{staticClass:"col-12 input-group input-inline"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.article_date),expression:"form.article_date"}],staticClass:"form-input",attrs:{"id":"article_date_input","maxlength":"10"},domProps:{"value":(_vm.form.article_date)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "article_date", $event.target.value)}}}),_c('button',{staticClass:"btn webfont-icon-only calendarPopper btn-primary",attrs:{"type":"button"}},[_vm._v("")])])])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",class:{ 'text-error': _vm.errors.display_from },attrs:{"for":"display_from_input"}},[_vm._v("Anzeige von")]),_c('div',{staticClass:"col-3"},[_c('div',{staticClass:"col-12 input-group input-inline"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.display_from),expression:"form.display_from"}],staticClass:"form-input",attrs:{"id":"display_from_input","maxlength":"10"},domProps:{"value":(_vm.form.display_from)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "display_from", $event.target.value)}}}),_c('button',{staticClass:"btn webfont-icon-only calendarPopper btn-primary",attrs:{"type":"button"}},[_vm._v("")])])])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",class:{ 'text-error': _vm.errors.display_until },attrs:{"for":"display_until_input"}},[_vm._v("Anzeige bis")]),_c('div',{staticClass:"col-3"},[_c('div',{staticClass:"col-12 input-group input-inline"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.display_until),expression:"form.display_until"}],staticClass:"form-input",attrs:{"id":"display_until_input","maxlength":"10"},domProps:{"value":(_vm.form.display_until)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "display_until", $event.target.value)}}}),_c('button',{staticClass:"btn webfont-icon-only calendarPopper btn-primary",attrs:{"type":"button"}},[_vm._v("")])])])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",class:{ 'text-error': _vm.errors.customsort },attrs:{"for":"custom_sort_input"}},[_vm._v("generische Sortierung")]),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.customsort),expression:"form.customsort"}],staticClass:"form-input col-2",attrs:{"id":"custom_sort_input","maxlength":"4"},domProps:{"value":(_vm.form.customsort)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "customsort", $event.target.value)}}})])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label"},[_vm._v("Markiert")]),_c('div',[_c('label',{staticClass:"form-switch"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.customflags),expression:"form.customflags"}],attrs:{"value":"1","type":"checkbox"},domProps:{"checked":Array.isArray(_vm.form.customflags)?_vm._i(_vm.form.customflags,"1")>-1:(_vm.form.customflags)},on:{"change":function($event){var $$a=_vm.form.customflags,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v="1",$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.form, "customflags", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.form, "customflags", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.form, "customflags", $$c)}}}}),_c('i',{staticClass:"form-icon"})])])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",class:{ 'text-error': _vm.errors.articlecategoriesid },attrs:{"for":"articlecategoriesid_select"}},[_c('strong',[_vm._v("*Kategorie")])]),_c('div',{staticClass:"col-9"},[_c('select',{staticClass:"form-select",attrs:{"id":"articlecategoriesid_select"}},_vm._l((_vm.options.categories),function(option){return _c('option',{domProps:{"value":option.articlesid}},[_vm._v(_vm._s(option.name))])}),0)])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",class:{ 'text-error': _vm.errors.headline },attrs:{"for":"headline_input"}},[_c('strong',[_vm._v("*Überschrift/Titel")])]),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.headline),expression:"form.headline"}],staticClass:"form-input",attrs:{"id":"headline_input","maxlength":"200"},domProps:{"value":(_vm.form.headline)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "headline", $event.target.value)}}})])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",attrs:{"for":"subline_input"}},[_vm._v("Unterüberschrift")]),_c('div',{staticClass:"col-9"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.subline),expression:"form.subline"}],staticClass:"form-input",attrs:{"id":"subline_input","maxlength":"200"},domProps:{"value":(_vm.form.subline)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "subline", $event.target.value)}}})])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",attrs:{"for":"teaser_input"}},[_vm._v("Anrisstext")]),_c('div',{staticClass:"col-9"},[_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.teaser),expression:"form.teaser"}],staticClass:"form-input",attrs:{"id":"teaser_input","rows":"3"},domProps:{"value":(_vm.form.teaser)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "teaser", $event.target.value)}}})])]),_c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label",class:{ 'text-error': _vm.errors.content },attrs:{"for":"content_input"}},[_c('strong',[_vm._v("*Seiteninhalt")])]),_c('div',{staticClass:"col-9"},[_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.content),expression:"form.content"}],staticClass:"form-input",attrs:{"id":"content_input","rows":"10"},domProps:{"value":(_vm.form.content)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "content", $event.target.value)}}})])]),_c('div',{staticClass:"divider"}),_vm._m(0)])}
var article_formvue_type_template_id_0f301980_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"form-group"},[_c('label',{staticClass:"col-3 form-label"}),_c('button',{staticClass:"btn btn-success",attrs:{"name":"submit_article","type":"submit"}},[_vm._v("Artikel anlegen")])])}]


// CONCATENATED MODULE: ./vue/components/forms/article-form.vue?vue&type=template&id=0f301980&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/forms/article-form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ var article_formvue_type_script_lang_js_ = ({

    props: {
        url: { type: String, required: true },
        initialData: { type: Object, default: () => { return {} } },
        options: { type: Object }
    },

    data: function() {
        return {
            form: {},
            response: {},
            loading: false
        }
    },

    computed: {
        errors () {
            return this.response ? (this.response.errors || {}) : {};
        },
        message () {
            return this.response ? this.response.message : "";
        }
    },

    watch: {
        initialData (newValue) {
            this.form = newValue;
        }
    },

    methods: {
        async submit() {
            this.loading = true;
            this.$emit("request-sent");
            this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
            this.loading = false;
            this.$emit("response-received");
        }
    }
});

// CONCATENATED MODULE: ./vue/components/forms/article-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_article_formvue_type_script_lang_js_ = (article_formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/forms/article-form.vue





/* normalize component */

var article_form_component = normalizeComponent(
  forms_article_formvue_type_script_lang_js_,
  article_formvue_type_template_id_0f301980_render,
  article_formvue_type_template_id_0f301980_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var article_form = (article_form_component.exports);
// CONCATENATED MODULE: ./vue/vxweb.js





















const Components = {
    MessageToast: message_toast,
    Autocomplete: autocomplete,
    DatePicker: datepicker,
    Sortable: sortable,
    SimpleTree: simple_tree,
    ZPagination: pagination,
    Filemanager: filemanager,
    Tab: tab,
    ZLink: zutre_link,
    ProfileForm: profile_form,
    UserForm: user_form,
    ArticleForm: article_form,
    SimpleFetch: SimpleFetch,
    PromisedXhr: PromisedXhr
};

const Filters = {
    formatFilesize: formatFilesize
};

const Directives = {
  Focus: Focus,
  Bubble: Bubble
};


// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js
/* concated harmony reexport Components */__webpack_require__.d(__webpack_exports__, "Components", function() { return Components; });
/* concated harmony reexport Filters */__webpack_require__.d(__webpack_exports__, "Filters", function() { return Filters; });
/* concated harmony reexport Directives */__webpack_require__.d(__webpack_exports__, "Directives", function() { return Directives; });




/***/ })

/******/ });
//# sourceMappingURL=vxweb.common.js.map