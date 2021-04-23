/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 214:
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(645);
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "div[data-v-0aec82d5]{position:relative}div a[data-v-0aec82d5]:focus{box-shadow:none;text-decoration:none;font-family:icomoon}div a[data-v-0aec82d5]:after{height:.8rem;margin:0 .25rem;position:absolute;top:50%;transform:translateY(-50%);width:.8rem;z-index:2;line-height:100%;right:.05rem;font-family:icomoon;content:\"\"}div a.show[data-v-0aec82d5]:after{content:\"\"}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ 589:
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(645);
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".sortable-header[data-v-3d275eb2]:after{font-family:icomoon;font-weight:400;vertical-align:middle;display:inline-block}.sortable-header.asc[data-v-3d275eb2]:after{content:\"\"}.sortable-header.desc[data-v-3d275eb2]:after{content:\"\"}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ 645:
/***/ ((module) => {

"use strict";


/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (cssWithMappingToString) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join("");
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === "string") {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, ""]];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

/***/ }),

/***/ 19:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(214);
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(346)/* .default */ .Z
var update = add("caa48e4c", content, true, {"sourceMap":false,"shadowMode":false});

/***/ }),

/***/ 685:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(589);
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.id, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__(346)/* .default */ .Z
var update = add("a5784f60", content, true, {"sourceMap":false,"shadowMode":false});

/***/ }),

/***/ 346:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "Z": () => (/* binding */ addStylesClient)
});

;// CONCATENATED MODULE: ./node_modules/vue-style-loader/lib/listToStyles.js
/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}

;// CONCATENATED MODULE: ./node_modules/vue-style-loader/lib/addStylesClient.js
/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/



var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

function addStylesClient (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			id: moduleId,
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	(() => {
/******/ 		__webpack_require__.p = "";
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "Components": () => (/* reexport */ Components),
  "Directives": () => (/* reexport */ Directives),
  "Plugins": () => (/* reexport */ Plugins),
  "Util": () => (/* reexport */ Util)
});

;// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
/* eslint-disable no-var */
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  var currentScript = window.document.currentScript
  if (false) { var getCurrentScript; }

  var src = currentScript && currentScript.src.match(/(.+\/)[^/]+\.js(\?.*)?$/)
  if (src) {
    __webpack_require__.p = src[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ const setPublicPath = (null);

;// CONCATENATED MODULE: external {"commonjs":"vue","commonjs2":"vue","root":"Vue"}
const external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject = require("vue");;
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/autocomplete.vue?vue&type=template&id=1e90eeda


function render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_autocomplete_input = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.resolveComponent)("autocomplete-input")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)($options.containerProps, { ref: "container" }), [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)(_component_autocomplete_input, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)({
      ref: "input",
      value: $props.modelValue
    }, $options.inputProps, {
      onInput: _cache[1] || (_cache[1] = $event => ($options.handleInput($event.target.value))),
      onKeydown: [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withKeys)($options.handleEnter, ["enter"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withKeys)($options.handleEsc, ["esc"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withKeys)($options.handleTab, ["tab"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withKeys)((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)($options.handleUp, ["prevent"]), ["up"]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withKeys)((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)($options.handleDown, ["prevent"]), ["down"])
      ],
      onFocus: $options.handleFocus,
      onBlur: $options.handleBlur
    }), null, 16, ["value", "onKeydown", "onFocus", "onBlur"]),
    ($data.results.length)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("ul", (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)({
          key: 0,
          ref: "resultList"
        }, $options.resultListProps, {
          onClick: _cache[2] || (_cache[2] = (...args) => ($options.handleResultClick && $options.handleResultClick(...args))),
          onMousedown: _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)(() => {}, ["prevent"]))
        }), [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($data.results, (result, index) => {
            return (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderSlot)(_ctx.$slots, "result", {
              result: result,
              props: $options.resultProps[index]
            }, () => [
              ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("li", (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)({
                key: $options.resultProps[index].id
              }, $options.resultProps[index]), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($props.getResultValue(result)), 17))
            ])
          }), 256))
        ], 16))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createCommentVNode)("", true)
  ], 16))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue?vue&type=template&id=1e90eeda

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-input.vue?vue&type=template&id=05532945


function form_inputvue_type_template_id_05532945_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("input", {
    value: $props.modelValue,
    class: "form-input",
    onInput: _cache[1] || (_cache[1] = $event => (_ctx.$emit('update:modelValue', $event.target.value)))
  }, null, 40, ["value"]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-input.vue?vue&type=template&id=05532945

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-input.vue?vue&type=script&lang=js

  /* harmony default export */ const form_inputvue_type_script_lang_js = ({
    name: 'form-input',
    props: ['modelValue'],
    emits: ['update:modelValue']
  });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-input.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-input.vue



form_inputvue_type_script_lang_js.render = form_inputvue_type_template_id_05532945_render

/* harmony default export */ const form_input = (form_inputvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/autocomplete.vue?vue&type=script&lang=js

  

  let uniqueId = function() {
    let counter = 0;
    return function(prefix) {
      return (prefix || "") + ++counter;
    }
  }();

  /* harmony default export */ const autocompletevue_type_script_lang_js = ({
    name: 'Autocomplete',
    inheritAttrs: false,

    components: {
      'autocomplete-input': form_input,
    },
    emits: ['update:modelValue', 'blur', 'submit'],
    props: {
      modelValue: {
        type: String,
        default: ""
      },
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
        default: 'autocomplete-input'
      },
      autoSelect: {
        type: Boolean,
        default: false
      },
      getResultValue: {
        type: Function,
        default: result => result
      }
    },

    data() {
      return {
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

    beforeUnmount() {
      document.body.removeEventListener('click', this.handleDocumentClick);
    },

    updated() {
      if (!this.$refs.resultList) {
        return;
      }

      let inputPos = this.$refs.input.$el.getBoundingClientRect();
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
      handleInput (value) {
        this.$emit('update:modelValue', value);
        this.updateResults(value);
      },

      handleFocus (event) {
        this.updateResults(event.target.value);
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
        this.$emit('update:modelValue', '');
      },

      handleEnter () {
        this.$emit('submit', this.selectResult());
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
          this.$emit('update:modelValue', this.getResultValue(selectedResult));
        }
        this.hideResults();
        return selectedResult;
      },

      handleResultClick (event) {
        const result = event.target.closest('[data-result-index]');
        if (result) {
          this.selectedIndex = parseInt(result.dataset.resultIndex, 10);
          this.$emit('submit', this.selectResult());
        }
      },

      handleDocumentClick (event) {
        if (this.$refs.container.contains(event.target)) {
          return;
        }
        this.hideResults();
      },

      updateResults (value) {

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

;// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/autocomplete.vue



autocompletevue_type_script_lang_js.render = render

/* harmony default export */ const autocomplete = (autocompletevue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/datepicker.vue?vue&type=template&id=10a22640


const _hoisted_1 = { class: "calendar-nav navbar" }
const _hoisted_2 = { class: "month navbar-primary" }
const _hoisted_3 = { class: "calendar-container" }
const _hoisted_4 = { class: "calendar-header" }
const _hoisted_5 = { class: "calendar-date" }
const _hoisted_6 = { class: "calendar-body" }

function datepickervue_type_template_id_10a22640_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_date_input = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.resolveComponent)("date-input")

  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", $options.rootProps, [
    ($props.hasInput)
      ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(_component_date_input, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)({
          key: 0,
          modelValue: $data.selectedDate,
          "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => ($data.selectedDate = $event)),
          onToggleDatepicker: $options.toggleDatepicker
        }, $options.inputProps, { ref: "input" }), null, 16, ["modelValue", "onToggleDatepicker"]))
      : (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createCommentVNode)("", true),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)({ class: "calendar" }, $options.calendarProps, {
      ref: "calendar",
      class: $data.align === 'left' ? 'align-left' : 'align-right'
    }), [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", _hoisted_1, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("button", {
          class: "btn btn-action btn-link btn-large prvMon",
          onClick: _cache[2] || (_cache[2] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)((...args) => ($options.previousMonth && $options.previousMonth(...args)), ["stop"]))
        }),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", _hoisted_2, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($options.monthLabel) + " " + (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($data.sheetDate.getFullYear()), 1),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("button", {
          class: "btn btn-action btn-link btn-large nxtMon",
          onClick: _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)((...args) => ($options.nextMonth && $options.nextMonth(...args)), ["stop"]))
        })
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", _hoisted_3, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", _hoisted_4, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($props.weekdays, (weekday) => {
            return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", _hoisted_5, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)(weekday), 1))
          }), 256))
        ]),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", _hoisted_6, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($options.days, (day) => {
            return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", {
              class: ["calendar-date text-center", ['prev-month', '', 'next-month'][day.getMonth() - $data.sheetDate.getMonth() + 1]]
            }, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("button", {
                type: "button",
                class: ["date-item", {
                  'active': $data.selectedDate && day.getTime() === $data.selectedDate.getTime(),
                  'date-today': day.getTime() === $options.today.getTime()
                }],
                disabled: ($props.validFrom && $props.validFrom) > day || ($props.validUntil && $props.validUntil < day),
                onClick: (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)($event => (($props.validFrom && $props.validFrom) > day || ($props.validUntil && $props.validUntil < day) ? null : $options.selectDate(day)), ["stop"])
              }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)(day.getDate()), 11, ["disabled", "onClick"])
            ], 2))
          }), 256))
        ])
      ])
    ], 16)
  ], 16))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue?vue&type=template&id=10a22640

;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/date-input.vue?vue&type=template&id=009accae


const date_inputvue_type_template_id_009accae_hoisted_1 = { class: "date-input" }
const date_inputvue_type_template_id_009accae_hoisted_2 = {
  key: 0,
  class: "form-input"
}
const date_inputvue_type_template_id_009accae_hoisted_3 = { class: "chip" }

function date_inputvue_type_template_id_009accae_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", date_inputvue_type_template_id_009accae_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", {
      class: "input-group",
      style: $options.computedStyles
    }, [
      ($options.dateString)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", date_inputvue_type_template_id_009accae_hoisted_2, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("span", date_inputvue_type_template_id_009accae_hoisted_3, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($options.dateString) + " ", 1),
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("a", {
                href: "#",
                class: "btn btn-clear",
                "aria-label": "Close",
                role: "button",
                onClick: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)((...args) => ($options.handleClear && $options.handleClear(...args)), ["prevent"]))
              })
            ])
          ]))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withDirectives)(((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("input", {
            key: 1,
            type: "text",
            class: "form-input",
            autocomplete: "off",
            "onUpdate:modelValue": _cache[2] || (_cache[2] = $event => ($data.inputString = $event)),
            onBlur: _cache[3] || (_cache[3] = (...args) => ($options.handleBlur && $options.handleBlur(...args))),
            onInput: _cache[4] || (_cache[4] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)(() => {}, ["stop"]))
          }, null, 544)), [
            [external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.vModelText, $data.inputString]
          ]),
      ($props.showButton)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("button", {
            key: 2,
            type: "button",
            class: "btn webfont-icon-only btn-primary input-group-btn",
            onClick: _cache[5] || (_cache[5] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)($event => (_ctx.$emit('toggle-datepicker')), ["stop"]))
          }, " "))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createCommentVNode)("", true)
    ], 4)
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue?vue&type=template&id=009accae

;// CONCATENATED MODULE: ./vue/util/date-functions.js
/* harmony default export */ const date_functions = ({

    formatDate(date, format, options) {

        if (!date instanceof Date) {
            return "";
        }

        const dayNames = options && options.dayNames ? options.dayNames : "Sun Mon Tue Wed Thu Fri Sat".split(" ");
        const monthNames = options && options.monthNames ? options.monthNames : "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" ");

        return format
            .replace(/\bd\b/, date.getDate())
            .replace(/\bdd\b/, ("0" + date.getDate()).slice(-2))
            .replace(/\bm\b/, date.getMonth() + 1)
            .replace(/\bmm\b/, ("0" + (date.getMonth() + 1)).slice(-2))
            .replace(/\bmmm\b/, monthNames[date.getMonth()].trim())
            .replace(/\by\b/, date.getFullYear())
            .replace(/\bw\b/, dayNames[date.getDay()].trim())
            .replace(/\bh\b/, ("0" + date.getHours()).slice(-2))
            .replace(/\bi\b/, ("0" + date.getMinutes()).slice(-2))
            .replace(/\bs\b/, ("0" + date.getSeconds()).slice(-2));
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
;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/date-input.vue?vue&type=script&lang=js



/* harmony default export */ const date_inputvue_type_script_lang_js = ({
  name: 'date-input',
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
    modelValue: Date
  },

  watch: {
    modelValue(value) {
      this.inputString = value ? date_functions.formatDate(value, this.outputFormat) : '';
    }
  },

  computed: {
    dateString() {
      return this.modelValue ? date_functions.formatDate(this.modelValue, this.outputFormat) : '';
    },
    computedStyles() {
      return {
        width: '100%'
      }
    }
  },

  methods: {
    handleBlur() {
      let date = date_functions.parseDate(this.inputString, this.inputFormat);
      this.$emit('update:modelValue', date || null);
    },
    handleClear() {
      this.$emit('update:modelValue', null);
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/date-input.vue



date_inputvue_type_script_lang_js.render = date_inputvue_type_template_id_009accae_render

/* harmony default export */ const date_input = (date_inputvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/datepicker.vue?vue&type=script&lang=js



/* harmony default export */ const datepickervue_type_script_lang_js = ({
  name: 'date-picker',
  components: {
    DateInput: date_input
  },
  emits: ['update:modelValue', 'month-change'],

  data() {
    return {
      sheetDate: null,
      selectedDate: null,
      expanded: !this.hasInput,
      align: 'left'
    };
  },

  watch: {
    modelValue(newValue) {
      if (newValue) {
        this.selectedDate = new Date(newValue.getFullYear(), newValue.getMonth(), newValue.getDate()); // crop any timedata
        this.sheetDate = new Date(this.selectedDate.getTime());
      }
      else {
        this.selectedDate = null;
        this.sheetDate = this.today;
      }
      this.sheetDate.setDate(1);
    },
    expanded(newValue) {
      if (newValue && this.hasInput) {
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
        style: {position: 'relative'}
      }
    },
    inputProps() {
      return {
        style: {position: 'relative'},
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
          display: this.expanded ? 'block' : 'none',
          position: 'absolute',
          top: '100%',
          transform: 'translateY(.2rem)',
          'z-index': 300
        } : {}
      }
    },
    days() {
      const dates = [], yr = this.sheetDate.getFullYear(), mon = this.sheetDate.getMonth();
      const nextMonth = new Date(yr, mon + 1, 0);
      const preceedingDays = (new Date(yr, mon, 0)).getDay() + 1 - this.startOfWeekIndex;
      const trailingDays = (7 - nextMonth.getDay()) % 7 - 1 + this.startOfWeekIndex;

      for (let i = -preceedingDays, j = nextMonth.getDate() + trailingDays; i < j; ++i) {
        dates.push(new Date(yr, mon, i + 1));
      }

      return (dates);
    },
    monthLabel() {
      return this.monthNames[this.sheetDate.getMonth()];
    },
    today() {
      const now = new Date();
      return new Date(now.getFullYear(), now.getMonth(), now.getDate());
    }
  },

  props: {
    modelValue: Date,
    validFrom: Date,
    validUntil: Date,
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
  created () {
    this.sheetDate = new Date(this.today.getTime());
    this.sheetDate.setDate(1);
  },
  mounted() {
    if (this.hasInput) {
      document.body.addEventListener('click', this.handleBodyClick);
    }
    if (this.modelValue) {
      this.sheetDate = new Date(this.modelValue.getTime());
      this.sheetDate.setDate(1);
      this.selectedDate = new Date(this.modelValue.getFullYear(), this.modelValue.getMonth(), this.modelValue.getDate());
    }
  },
  beforeUnmount() {
    document.body.removeEventListener('click', this.handleBodyClick);
  },

  methods: {
    previousMonth() {
      this.sheetDate = new Date(this.sheetDate.getFullYear(), this.sheetDate.getMonth() - 1, 1);
      this.$emit("month-change", this.sheetDate);
    },
    nextMonth() {
      this.sheetDate = new Date(this.sheetDate.getFullYear(), this.sheetDate.getMonth() + 1, 1);
      this.$emit("month-change", this.sheetDate);
    },
    selectDate(day) {
      this.selectedDate = day;
      this.$emit('update:modelValue', day);
      this.expanded = !this.hasInput;
    },
    toggleDatepicker() {
      this.expanded = !this.expanded;
    },
    handleBodyClick() {
      this.expanded = false;
    },
    handleInput(date) {
      this.selectedDate = date;
      this.$emit('update:modelValue', date);
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/datepicker.vue



datepickervue_type_script_lang_js.render = datepickervue_type_template_id_10a22640_render

/* harmony default export */ const datepicker = (datepickervue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/confirm.vue?vue&type=template&id=09de9d7a


const confirmvue_type_template_id_09de9d7a_hoisted_1 = { class: "modal-container" }
const confirmvue_type_template_id_09de9d7a_hoisted_2 = {
  key: 0,
  class: "modal-header bg-error text-light"
}
const confirmvue_type_template_id_09de9d7a_hoisted_3 = { class: "modal-title h5" }
const confirmvue_type_template_id_09de9d7a_hoisted_4 = { class: "modal-body" }
const confirmvue_type_template_id_09de9d7a_hoisted_5 = { class: "content" }
const confirmvue_type_template_id_09de9d7a_hoisted_6 = { class: "modal-footer" }

function confirmvue_type_template_id_09de9d7a_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", {
    ref: "container",
    class: ["modal modal-sm", { active: $data.show }],
    onKeydown: _cache[4] || (_cache[4] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withKeys)((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)((...args) => ($options.cancel && $options.cancel(...args)), ["stop"]), ["esc"]))
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("a", {
      href: "#close",
      class: "modal-overlay",
      onClick: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)((...args) => ($options.cancel && $options.cancel(...args)), ["prevent"]))
    }),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_1, [
      ($data.title)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", confirmvue_type_template_id_09de9d7a_hoisted_2, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_3, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($data.title), 1)
          ]))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createCommentVNode)("", true),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_4, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_5, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($data.message), 1)
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", confirmvue_type_template_id_09de9d7a_hoisted_6, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("button", {
          type: "button",
          class: ["btn mr-2", $data.options.okClass],
          onClick: _cache[2] || (_cache[2] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)((...args) => ($options.ok && $options.ok(...args)), ["prevent"])),
          ref: "okButton"
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($data.options.okLabel), 3),
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("button", {
          type: "button",
          class: "btn btn-link",
          onClick: _cache[3] || (_cache[3] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)((...args) => ($options.cancel && $options.cancel(...args)), ["prevent"]))
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($data.options.cancelLabel), 1)
      ])
    ])
  ], 34))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue?vue&type=template&id=09de9d7a

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/confirm.vue?vue&type=script&lang=js

/* heavily inspired by https://gist.github.com/eolant/ba0f8a5c9135d1a146e1db575276177d */

/* harmony default export */ const confirmvue_type_script_lang_js = ({
  name: 'confirm',

  props: {
    config: {
      type: Object
    }
  },

  data() {
    return {
      title: "",
      message: "",
      show: false,
      resolve: null,
      reject: null,
      options: {
        okLabel: "Ok",
        cancelLabel: "Cancel",
        okClass: "btn-success"
      }
    }
  },

  watch: {
    config() {
      this.options = Object.assign({}, this.options, this.config);
    }
  },

  created() {
    this.options = Object.assign({}, this.options, this.config);
  },

  methods: {
    open(title, message, options) {
      this.title = title;
      this.message = message;
      this.show = true;
      this.$nextTick(() => this.$refs.okButton.focus());
      this.options = Object.assign(this.options, options || {});
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },
    ok() {
      this.show = false;
      this.resolve(true);
    },
    cancel() {
      this.show = false;
      this.resolve(false);
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/confirm.vue



confirmvue_type_script_lang_js.render = confirmvue_type_template_id_09de9d7a_render

/* harmony default export */ const vx_vue_confirm = (confirmvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/alert.vue?vue&type=template&id=9bfcbb82


const alertvue_type_template_id_9bfcbb82_hoisted_1 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", { class: "modal-overlay" }, null, -1)
const alertvue_type_template_id_9bfcbb82_hoisted_2 = { class: "modal-container" }
const alertvue_type_template_id_9bfcbb82_hoisted_3 = {
  key: 0,
  class: "modal-header bg-error text-light"
}
const alertvue_type_template_id_9bfcbb82_hoisted_4 = { class: "modal-title h5" }
const alertvue_type_template_id_9bfcbb82_hoisted_5 = { class: "modal-body" }
const alertvue_type_template_id_9bfcbb82_hoisted_6 = { class: "content" }
const _hoisted_7 = { class: "modal-footer" }

function alertvue_type_template_id_9bfcbb82_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", {
    ref: "container",
    class: ["modal modal-sm", { active: $data.show }]
  }, [
    alertvue_type_template_id_9bfcbb82_hoisted_1,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_2, [
      ($data.title)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", alertvue_type_template_id_9bfcbb82_hoisted_3, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_4, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($data.title), 1)
          ]))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createCommentVNode)("", true),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_5, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", alertvue_type_template_id_9bfcbb82_hoisted_6, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($data.message), 1)
      ]),
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("div", _hoisted_7, [
        (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("button", {
          type: "button",
          class: ["btn", $data.options.buttonClass],
          onClick: _cache[1] || (_cache[1] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)((...args) => ($options.ok && $options.ok(...args)), ["prevent"])),
          ref: "button"
        }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($data.options.label), 3)
      ])
    ])
  ], 2))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue?vue&type=template&id=9bfcbb82

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/alert.vue?vue&type=script&lang=js

    /* harmony default export */ const alertvue_type_script_lang_js = ({
        name: 'alert',

        props: {
            config: {
                type: Object
            }
        },

        data () { return {
            title: "",
            message: "",
            show: false,
            resolve: null,
            reject: null,
            options: {
                label: "Ok",
                buttonClass: "btn-success"
            }
        }},

        watch: {
            config() {
                this.options = Object.assign({}, this.options, this.config);
            }
        },

        created() {
            this.options = Object.assign({}, this.options, this.config);
        },

        methods: {
            open (title, message, options) {
                this.title = title;
                this.message = message;
                this.show = true;
                this.$nextTick(() => this.$refs.button.focus());
                this.options = Object.assign(this.options, options || {});
                return new Promise((resolve, reject) => {
                    this.resolve = resolve;
                    this.reject = reject;
                });
            },
            ok () {
                this.show = false;
                this.resolve(true);
            }
        }
    });

;// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/alert.vue



alertvue_type_script_lang_js.render = alertvue_type_template_id_9bfcbb82_render

/* harmony default export */ const vx_vue_alert = (alertvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/message-toast.vue?vue&type=template&id=fd1e9b62


function message_toastvue_type_template_id_fd1e9b62_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", {
    id: "messageBox",
    class: [{ 'display': $props.active }, $props.classname, 'toast']
  }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("button", {
      class: "btn btn-clear float-right",
      onClick: _cache[1] || (_cache[1] = $event => (_ctx.$emit('cancel')))
    }),
    ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($options.lines, (line) => {
      return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)(line), 1))
    }), 256))
  ], 2))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue?vue&type=template&id=fd1e9b62

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/message-toast.vue?vue&type=script&lang=js

/* harmony default export */ const message_toastvue_type_script_lang_js = ({
  name: 'message-toast',
  emits: ['timeout', 'cancel'],
  props: {
    message: [String, Array],
    classname: String,
    timeout: {type: Number, default: 5000},
    active: {type: Boolean, default: false}
  },
  data() {
    return {
      activeTimeout: null
    }
  },
  computed: {
    lines() {
      return typeof this.message === 'string' ? [this.message] : this.message;
    }
  },
  watch: {
    active() {
      this.setTimeout();
    }
  },

  mounted() {
    this.setTimeout();
  },

  methods: {
    setTimeout() {
      window.clearTimeout(this.activeTimeout);

      // timeout 0 disables fadeout

      if (this.active && this.timeout) {
        this.activeTimeout = window.setTimeout(() => {
          this.$emit('timeout');
        }, this.timeout);
      }
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/message-toast.vue



message_toastvue_type_script_lang_js.render = message_toastvue_type_template_id_fd1e9b62_render

/* harmony default export */ const message_toast = (message_toastvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/password-input.vue?vue&type=template&id=0aec82d5&scoped=true

const _withId = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withScopeId)("data-v-0aec82d5")

;(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.pushScopeId)("data-v-0aec82d5")
const password_inputvue_type_template_id_0aec82d5_scoped_true_hoisted_1 = { class: "form-group is-password" }
;(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.popScopeId)()

const password_inputvue_type_template_id_0aec82d5_scoped_true_render = /*#__PURE__*/_withId((_ctx, _cache, $props, $setup, $data, $options) => {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", password_inputvue_type_template_id_0aec82d5_scoped_true_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("input", (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)(_ctx.$attrs, {
      value: $props.modelValue,
      class: "form-input",
      type: $data.show ? 'text': 'password',
      onInput: _cache[1] || (_cache[1] = $event => (_ctx.$emit('update:modelValue', $event.target.value)))
    }), null, 16, ["value", "type"]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("a", {
      class: { 'show': $data.show },
      href: "#",
      onClick: _cache[2] || (_cache[2] = (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withModifiers)($event => ($data.show = !$data.show), ["prevent"]))
    }, null, 2)
  ]))
})
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=template&id=0aec82d5&scoped=true

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/password-input.vue?vue&type=script&lang=js

    /* harmony default export */ const password_inputvue_type_script_lang_js = ({
      name: 'password-input',
      inheritAttrs: false,
      props: ['modelValue'],
      emits: ['update:modelValue'],
      data () { return {
        show: false
      }}
    });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=script&lang=js
 
// EXTERNAL MODULE: ./node_modules/vue-style-loader/index.js??clonedRuleSet-21.use[0]!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-21.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-21.use[2]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-21.use[3]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-21.use[4]!./node_modules/style-resources-loader/lib/index.js??clonedRuleSet-21.use[5]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/password-input.vue?vue&type=style&index=0&id=0aec82d5&scoped=true&lang=scss
var password_inputvue_type_style_index_0_id_0aec82d5_scoped_true_lang_scss = __webpack_require__(19);
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue?vue&type=style&index=0&id=0aec82d5&scoped=true&lang=scss

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/password-input.vue




;
password_inputvue_type_script_lang_js.render = password_inputvue_type_template_id_0aec82d5_scoped_true_render
password_inputvue_type_script_lang_js.__scopeId = "data-v-0aec82d5"

/* harmony default export */ const password_input = (password_inputvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/pagination.vue?vue&type=template&id=7c778d31


const paginationvue_type_template_id_7c778d31_hoisted_1 = { class: "pagination" }
const paginationvue_type_template_id_7c778d31_hoisted_2 = { key: 1 }

function paginationvue_type_template_id_7c778d31_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", null, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("ul", paginationvue_type_template_id_7c778d31_hoisted_1, [
      ($props.showNavButtons)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("li", {
            key: 0,
            class: ["page-item", { disabled: $data.currentPage <= 1 }]
          }, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("a", {
              tabindex: "-1",
              onClick: _cache[1] || (_cache[1] = (...args) => ($options.prevPage && $options.prevPage(...args))),
              class: "menu-item"
            }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($props.prevText), 1)
          ], 2))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createCommentVNode)("", true),
      ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($options.pagesToShow, (page, idx) => {
        return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("li", {
          key: idx,
          class: ["page-item", {active: $data.currentPage === page}]
        }, [
          (page !== 'dots')
            ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("a", {
                key: 0,
                onClick: $event => ($options.pageClick(page))
              }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)(page), 9, ["onClick"]))
            : ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("span", paginationvue_type_template_id_7c778d31_hoisted_2, "…"))
        ], 2))
      }), 128)),
      ($props.showNavButtons)
        ? ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("li", {
            key: 1,
            class: ["page-item", { disabled: $data.currentPage >= $data.maxPage }]
          }, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("a", {
              tabindex: "-1",
              onClick: _cache[2] || (_cache[2] = (...args) => ($options.nextPage && $options.nextPage(...args)))
            }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($props.nextText), 1)
          ], 2))
        : (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createCommentVNode)("", true)
    ])
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue?vue&type=template&id=7c778d31

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/pagination.vue?vue&type=script&lang=js


/* harmony default export */ const paginationvue_type_script_lang_js = ({
  name: 'pagination',
  emits: ['update:page', 'update:total'],
  props: {
    items: Array,
    total: { type: Number, default: 1 },
    page: { type: Number, default: 1 },
    perPage: { type: Number, default: 20 },
    showNavButtons: { type: Boolean, default: true },
    prevText: { type: String, default: 'Previous' },
    nextText: { type: String, default: 'Next' },
    showAllPages: { type: Boolean, default: false },
    onPageChange: Function
  },
  data () {
    return {
      currentPage: 1,
      maxPage: 0,
      showPerPage: 20,
      dataItems: undefined,
    };
  },
  created () {
    this.currentPage = this.page;
    this.totalResults = this.total;
    this.showPerPage = this.perPage;

    if (typeof this.items !== 'undefined') {
      this.dataItems = this.items;
      this.totalResults = this.items.length;
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
        let i = 2;
        for (; i <= this.maxPage; ++i) {
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
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/pagination.vue



paginationvue_type_script_lang_js.render = paginationvue_type_template_id_7c778d31_render

/* harmony default export */ const pagination = (paginationvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-select.vue?vue&type=template&id=7295af77


function form_selectvue_type_template_id_7295af77_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("select", (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)(_ctx.$attrs, {
    value: $props.modelValue,
    class: "form-select",
    onChange: _cache[1] || (_cache[1] = $event => (_ctx.$emit('update:modelValue', $event.target.value)))
  }), [
    ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($props.options, (option) => {
      return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("option", {
        value: option.key || option.label || option,
        selected: (option.key || option.label || option) == $props.modelValue
      }, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)(option.label || option), 9, ["value", "selected"]))
    }), 256))
  ], 16, ["value"]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue?vue&type=template&id=7295af77

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-select.vue?vue&type=script&lang=js

    /* harmony default export */ const form_selectvue_type_script_lang_js = ({
      name: 'form-select',
      props: { options: Array, modelValue: [String, Number] },
      emits: ['update:modelValue']
    });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-select.vue



form_selectvue_type_script_lang_js.render = form_selectvue_type_template_id_7295af77_render

/* harmony default export */ const form_select = (form_selectvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-switch.vue?vue&type=template&id=6622bb0e


const form_switchvue_type_template_id_6622bb0e_hoisted_1 = { class: "form-switch" }
const form_switchvue_type_template_id_6622bb0e_hoisted_2 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("i", { class: "form-icon" }, null, -1)

function form_switchvue_type_template_id_6622bb0e_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("label", form_switchvue_type_template_id_6622bb0e_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("input", (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)({
      value: "1",
      type: "checkbox",
      onChange: _cache[1] || (_cache[1] = $event => (_ctx.$emit('update:modelValue', $event.target.checked)))
    }, _ctx.$attrs, { checked: $props.modelValue }), null, 16, ["checked"]),
    form_switchvue_type_template_id_6622bb0e_hoisted_2,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderSlot)(_ctx.$slots, "default")
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-switch.vue?vue&type=template&id=6622bb0e

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-switch.vue?vue&type=script&lang=js

  /* harmony default export */ const form_switchvue_type_script_lang_js = ({
    name: 'form-switch',
    inheritAttrs: false,
    props: ['modelValue'],
    emits: ['update:modelValue']
  });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-switch.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-switch.vue



form_switchvue_type_script_lang_js.render = form_switchvue_type_template_id_6622bb0e_render

/* harmony default export */ const form_switch = (form_switchvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=template&id=6b569d2a


const form_checkboxvue_type_template_id_6b569d2a_hoisted_1 = { class: "form-checkbox" }
const form_checkboxvue_type_template_id_6b569d2a_hoisted_2 = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("i", { class: "form-icon" }, null, -1)

function form_checkboxvue_type_template_id_6b569d2a_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("label", form_checkboxvue_type_template_id_6b569d2a_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("input", (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.mergeProps)({
      value: "1",
      type: "checkbox",
      onChange: _cache[1] || (_cache[1] = $event => (_ctx.$emit('update:modelValue', $event.target.checked)))
    }, _ctx.$attrs, { checked: $props.modelValue }), null, 16, ["checked"]),
    form_checkboxvue_type_template_id_6b569d2a_hoisted_2,
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderSlot)(_ctx.$slots, "default")
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=template&id=6b569d2a

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=script&lang=js

  /* harmony default export */ const form_checkboxvue_type_script_lang_js = ({
    name: 'form-checkbox',
    inheritAttrs: false,
    props: ['modelValue'],
    emits: ['update:modelValue']
  });

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-checkbox.vue



form_checkboxvue_type_script_lang_js.render = form_checkboxvue_type_template_id_6b569d2a_render

/* harmony default export */ const form_checkbox = (form_checkboxvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-file-button.vue?vue&type=template&id=f32da906


function form_file_buttonvue_type_template_id_f32da906_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("label", { for: $props.id }, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)($props.label) + " ", 1),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("input", {
      type: "file",
      id: $props.id,
      multiple: $props.multiple,
      accept: $props.accept,
      onChange: _cache[1] || (_cache[1] = (...args) => ($options.fileChanged && $options.fileChanged(...args))),
      class: "d-none"
    }, null, 40, ["id", "multiple", "accept"])
  ], 8, ["for"]))
}
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-file-button.vue?vue&type=template&id=f32da906

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/formelements/form-file-button.vue?vue&type=script&lang=js

/* harmony default export */ const form_file_buttonvue_type_script_lang_js = ({
  name: 'form-file-button',
  props: {
    modelValue: { type: Array },
    accept: { type: String, default: "*" },
    multiple: { type: Boolean, default: false },
    name: { type: String, default: "file" },
    label: { type: String, default: 'Upload' },
    id: { type: String, default: 'file_upload' }
  },

  emits: ['update:modelValue', 'form-data'],

  methods: {
    getFormData(files) {
      const data = new FormData();
      const name = this.name + (this.multiple ? "[]" : "");
      for (let file of files) {
        data.append(name, file, file.name);
      }
      return data;
    },
    fileChanged (event) {
      let files = event.target.files || event.dataTransfer.files;
      if (files) {

        // convert FileList to Array

        files = [...files];
        this.$emit('update:modelValue', files);
        this.$emit('form-data', this.getFormData(files));
      }
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-file-button.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vx-vue/formelements/form-file-button.vue



form_file_buttonvue_type_script_lang_js.render = form_file_buttonvue_type_template_id_f32da906_render

/* harmony default export */ const form_file_button = (form_file_buttonvue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/sortable.vue?vue&type=template&id=3d275eb2&scoped=true

const sortablevue_type_template_id_3d275eb2_scoped_true_withId = /*#__PURE__*/(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.withScopeId)("data-v-3d275eb2")

;(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.pushScopeId)("data-v-3d275eb2")
const sortablevue_type_template_id_3d275eb2_scoped_true_hoisted_1 = { class: "table table-striped" }
;(0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.popScopeId)()

const sortablevue_type_template_id_3d275eb2_scoped_true_render = /*#__PURE__*/sortablevue_type_template_id_3d275eb2_scoped_true_withId((_ctx, _cache, $props, $setup, $data, $options) => {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("table", sortablevue_type_template_id_3d275eb2_scoped_true_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("thead", null, [
      (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("tr", null, [
        ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($props.columns, (column) => {
          return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("th", {
            class: [
                    { 'sortable-header': column.sortable },
                    column.cssClass,
                    $data.sortColumn === column ? $data.sortDir : null,
                    column.width
                ],
            onClick: $event => (column.sortable ? $options.clickSort(column) : null)
          }, [
            (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderSlot)(_ctx.$slots, column.prop + '-header', { column: column }, () => [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)(column.label), 1)
            ], true)
          ], 10, ["onClick"]))
        }), 256))
      ])
    ]),
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("tbody", null, [
      ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($options.sortedRows, (row) => {
        return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("tr", {
          key: row[$props.keyProperty],
          class: row.cssClass
        }, [
          ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(true), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)(external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.Fragment, null, (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderList)($props.columns, (column) => {
            return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("td", {
              class: { 'active': $data.sortColumn === column }
            }, [
              (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.renderSlot)(_ctx.$slots, column.prop, { row: row }, () => [
                (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createTextVNode)((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.toDisplayString)(row[column.prop]), 1)
              ], true)
            ], 2))
          }), 256))
        ], 2))
      }), 128))
    ])
  ]))
})
;// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue?vue&type=template&id=3d275eb2&scoped=true

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/sortable.vue?vue&type=script&lang=js

    /* harmony default export */ const sortablevue_type_script_lang_js = ({
        name: 'sortable',
        emits: ['before-sort', 'after-sort'],
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
            offset: {
                type: Number,
                default: null
            },
            count: {
                type: Number,
                default: null
            },
            sortProp: {
                type: String
            },
            sortDirection: {
                type: String,
                validator (val) {
                    return !val || ['asc', 'desc'].indexOf(val) !== -1;
                }
            },
            keyProperty: {
              type: String,
              default: 'key'
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
                let rows = this.rows.slice();

                if(this.sortColumn) {
                    if (this.sortDir === 'asc' && this.sortColumn.sortAscFunction) {
                        rows.sort (this.sortColumn.sortAscFunction);
                    }
                    else if (this.sortDir === 'desc' && this.sortColumn.sortDescFunction) {
                        rows.sort (this.sortColumn.sortDescFunction);
                    }
                    else {
                        let prop = this.sortColumn.prop;

                        rows.sort((a, b) => {
                            if (a[prop] < b[prop]) {
                                return this.sortDir === "asc" ? -1 : 1;
                            }
                            if (a[prop] > b[prop]) {
                                return this.sortDir === "asc" ? 1 : -1;
                            }
                            return 0;
                        });
                    }
                }

                let offset = this.offset || 0, count = this.count || rows.length;

                return rows.slice(offset, offset + count);
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
                    this.sortDir = this.sortDir || 'asc';
                }
                this.$nextTick( () => this.$emit('after-sort') );
            }
        }
    });

;// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue?vue&type=script&lang=js
 
// EXTERNAL MODULE: ./node_modules/vue-style-loader/index.js??clonedRuleSet-21.use[0]!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-21.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-21.use[2]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-21.use[3]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-21.use[4]!./node_modules/style-resources-loader/lib/index.js??clonedRuleSet-21.use[5]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vx-vue/sortable.vue?vue&type=style&index=0&id=3d275eb2&scoped=true&lang=scss
var sortablevue_type_style_index_0_id_3d275eb2_scoped_true_lang_scss = __webpack_require__(685);
;// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue?vue&type=style&index=0&id=3d275eb2&scoped=true&lang=scss

;// CONCATENATED MODULE: ./vue/components/vx-vue/sortable.vue




;
sortablevue_type_script_lang_js.render = sortablevue_type_template_id_3d275eb2_scoped_true_render
sortablevue_type_script_lang_js.__scopeId = "data-v-3d275eb2"

/* harmony default export */ const sortable = (sortablevue_type_script_lang_js);
;// CONCATENATED MODULE: ./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vue-ckeditor.vue?vue&type=template&id=12f62f34


const vue_ckeditorvue_type_template_id_12f62f34_hoisted_1 = { class: "ckeditor" }

function vue_ckeditorvue_type_template_id_12f62f34_render(_ctx, _cache, $props, $setup, $data, $options) {
  return ((0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.openBlock)(), (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createBlock)("div", vue_ckeditorvue_type_template_id_12f62f34_hoisted_1, [
    (0,external_commonjs_vue_commonjs2_vue_root_Vue_namespaceObject.createVNode)("textarea", {
      name: $props.name,
      id: $props.id,
      value: $props.modelValue,
      types: $props.types,
      config: $props.config,
      disabled: $props.readOnlyMode
    }, "\n    ", 8, ["name", "id", "value", "types", "config", "disabled"])
  ]))
}
;// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue?vue&type=template&id=12f62f34

;// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ruleSet[0].use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[1]!./vue/components/vue-ckeditor.vue?vue&type=script&lang=js

let inc = new Date().getTime();

/* harmony default export */ const vue_ckeditorvue_type_script_lang_js = ({
  name: 'vue-ckeditor',
  emits: ['blur', 'focus', 'contentDom', 'dialogDefinition', 'fileUploadRequest', 'fileUploadResponse', 'update:modelValue'],
  props: {
    modelValue: String,
    name: { type: String, default: `editor-${++inc}` },
    id: { type: String, default:`editor-${inc}` },
    types: { type: String, default: `classic` },
    config: { type: Object, default: {} },
    instanceReadyCallback: Function,
    readOnlyMode: { type: Boolean, default: false }
  },
  data: () => ({
      instanceValue: ''
  }),
  computed: {
    instance() {
      return CKEDITOR.instances[this.id];
    }
  },
  watch: {
    modelValue(val) {
      try {
        if (this.instance) {
          this.update(val);
        }
      } catch (e) {}
    },
    readOnlyMode(val) {
      this.instance.setReadOnly(val);
    }
  },
  mounted() {
    if (typeof CKEDITOR === 'undefined') {
      console.log('CKEDITOR is missing (http://ckeditor.com/)');
    } else {
      if (this.types === 'inline') {
        CKEDITOR.inline(this.id, this.config);
      } else {
        CKEDITOR.replace(this.id, this.config);
      }

      this.instance.setData(this.modelValue);

      this.instance.on('instanceReady', () => {
        this.instance.setData(this.modelValue);
      });

      // Ckeditor change event
      this.instance.on('change', this.onChange);

      // Ckeditor mode html or source
      this.instance.on('mode', this.onMode);

      // Ckeditor blur event
      this.instance.on('blur', evt => { this.$emit('blur', evt) });

      // Ckeditor focus event
      this.instance.on('focus', evt => { this.$emit('focus', evt) });

      // Ckeditor contentDom event
      this.instance.on('contentDom', evt => { this.$emit('contentDom', evt) });

      // Ckeditor dialog definition event
      CKEDITOR.on('dialogDefinition', evt => { this.$emit('dialogDefinition', evt) });

      // Ckeditor file upload request event
      this.instance.on('fileUploadRequest', evt => { this.$emit('fileUploadRequest', evt) });

      // Ckditor file upload response event
      this.instance.on('fileUploadResponse', evt => {
        this.$nextTick( () => { this.onChange() });
        this.$emit('fileUploadResponse', evt);
      });

      // Listen for instanceReady event
      if (typeof this.instanceReadyCallback !== 'undefined') {
        this.instance.on('instanceReady', this.instanceReadyCallback);
      }
    }
  },
  beforeUnmount() {
    try {
      let editor = window['CKEDITOR'];
      if (editor.instances && editor.instances[this.id]) {
        editor.instances[this.id].destroy();
      }
    } catch (e) {}
  },
  methods: {
    update(val) {
      if (this.instanceValue !== val) {
        this.instance.setData(val, { internal: false });
        this.instanceValue = val;
      }
    },
    onMode() {
      if (this.instance.mode === 'source') {
        let editable = this.instance.editable();
        editable.attachListener(editable, 'input', () => {
          this.onChange();
        });
      }
    },
    onChange() {
      let html = this.instance.getData();
      if (html !== this.modelValue) {
        this.$emit('update:modelValue', html);
        this.instanceValue = html;
      }
    }
  }
});

;// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue?vue&type=script&lang=js
 
;// CONCATENATED MODULE: ./vue/components/vue-ckeditor.vue



vue_ckeditorvue_type_script_lang_js.render = vue_ckeditorvue_type_template_id_12f62f34_render

/* harmony default export */ const vue_ckeditor = (vue_ckeditorvue_type_script_lang_js);
;// CONCATENATED MODULE: ./vue/util/simple-fetch.js
async function SimpleFetch(url, method = 'GET', headers = {}, payload = null) {

    if(!headers['X-CSRF-Token'] && document.querySelector('meta[name="csrf-token"]')) {
        headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    }
    headers['X-Requested-With'] = 'XMLHttpRequest';
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

;// CONCATENATED MODULE: ./vue/util/promised-xhr.js
function PromisedXhr(url, method = 'GET', headers = {}, payload = null, timeout = null, progressCallback = null, cancelToken = null) {

    if(!headers['X-CSRF-Token'] && document.querySelector('meta[name="csrf-token"]')) {
        headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    }
    headers['Content-type'] = headers['Content-type'] || 'application/x-www-form-urlencoded';
    headers['X-Requested-With'] = 'XMLHttpRequest';

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

;// CONCATENATED MODULE: ./vue/util/url-query.js
/* harmony default export */ const url_query = ({
    /**
     * @return {string}
     */
    create (url, query = {}) {
        return url + (url.indexOf('?') !== -1 ? '&' : '?') + this.objectToSearch(query);
    },

    /* @see https://stackoverflow.com/questions/8648892/how-to-convert-url-parameters-to-a-javascript-object */

    searchToObject (query) {
        return Object.fromEntries(new URLSearchParams(query));
    },

    /* @see https://stackoverflow.com/questions/1714786/query-string-encoding-of-a-javascript-object */

    objectToSearch (obj) {
        return new URLSearchParams(obj).toString();
    }
});

;// CONCATENATED MODULE: ./vue/util/bytes-to-size.js
/* harmony default export */ const bytes_to_size = ({
    formatBytes (bytes, decimals = 2, base = 1024) {
        let i = Math.floor(Math.log(bytes) / Math.log(base));
        return (bytes /Math.pow(base, i)).toFixed(decimals) + " " + ("KMGTPEZY"[i-1] || "") + "B";
    }
});
;// CONCATENATED MODULE: ./vue/directives.js
// focus an element upon insertion

const Focus = { mounted: (el) => el.focus() };



;// CONCATENATED MODULE: ./vue/build/sample.js






















const Components = {
    FormSelect: form_select,
    FormSwitch: form_switch,
    FormCheckbox: form_checkbox,
    FormFileButton: form_file_button,
    PasswordInput: password_input,
    Autocomplete: autocomplete,
    Datepicker: datepicker,
    Confirm: vx_vue_confirm,
    Alert: vx_vue_alert,
    MessageToast: message_toast,
    Pagination: pagination,
    Sortable: sortable,
    CKEditor: vue_ckeditor
};

const Directives = {
    Focus: Focus
};

const Plugins = {
};

const Util = {
    SimpleFetch: SimpleFetch,
    PromisedXhr: PromisedXhr,
    UrlQuery: url_query,
    DateFunctions: date_functions,
    BytesToSize: bytes_to_size
};


;// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js



})();

module.exports = __webpack_exports__;
/******/ })()
;
//# sourceMappingURL=sample.common.js.map