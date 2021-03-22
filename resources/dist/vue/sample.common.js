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

/***/ "24fb":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (useSourceMap) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item, useSourceMap);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join('');
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === 'string') {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, '']];
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

function cssWithMappingToString(item, useSourceMap) {
  var content = item[1] || ''; // eslint-disable-next-line prefer-destructuring

  var cssMapping = item[3];

  if (!cssMapping) {
    return content;
  }

  if (useSourceMap && typeof btoa === 'function') {
    var sourceMapping = toComment(cssMapping);
    var sourceURLs = cssMapping.sources.map(function (source) {
      return "/*# sourceURL=".concat(cssMapping.sourceRoot || '').concat(source, " */");
    });
    return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
  }

  return [content].join('\n');
} // Adapted from convert-source-map (MIT)


function toComment(sourceMap) {
  // eslint-disable-next-line no-undef
  var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
  var data = "sourceMappingURL=data:application/json;charset=utf-8;base64,".concat(base64);
  return "/*# ".concat(data, " */");
}

/***/ }),

/***/ "499e":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "default", function() { return /* binding */ addStylesClient; });

// CONCATENATED MODULE: ./node_modules/vue-style-loader/lib/listToStyles.js
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

// CONCATENATED MODULE: ./node_modules/vue-style-loader/lib/addStylesClient.js
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


/***/ }),

/***/ "6017":
/***/ (function(module, exports, __webpack_require__) {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__("24fb");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.i, "div[data-v-3030fede]{position:relative}div a[data-v-3030fede]:focus{box-shadow:none;text-decoration:none;font-family:icomoon}div a[data-v-3030fede]:after{height:.8rem;margin:0 .25rem;position:absolute;top:50%;transform:translateY(-50%);width:.8rem;z-index:2;line-height:100%;right:.05rem;font-family:icomoon;content:\"\\e015\"}div a.show[data-v-3030fede]:after{content:\"\\e016\"}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "cb59":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_vue_style_loader_index_js_ref_8_oneOf_1_0_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_2_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_node_modules_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_password_input_vue_vue_type_style_index_0_id_3030fede_scoped_true_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("ea57");
/* harmony import */ var _node_modules_vue_style_loader_index_js_ref_8_oneOf_1_0_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_2_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_node_modules_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_password_input_vue_vue_type_style_index_0_id_3030fede_scoped_true_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_style_loader_index_js_ref_8_oneOf_1_0_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_2_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_node_modules_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_password_input_vue_vue_type_style_index_0_id_3030fede_scoped_true_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "ea57":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("6017");
if(content.__esModule) content = content.default;
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var add = __webpack_require__("499e").default
var update = add("0bc1c022", content, true, {"sourceMap":false,"shadowMode":false});

/***/ }),

/***/ "fae3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "Components", function() { return /* reexport */ Components; });
__webpack_require__.d(__webpack_exports__, "Directives", function() { return /* reexport */ Directives; });
__webpack_require__.d(__webpack_exports__, "Util", function() { return /* reexport */ Util; });

// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
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
/* harmony default export */ var setPublicPath = (null);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"7b388e96-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/autocomplete.vue?vue&type=template&id=12f44c8c&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',_vm._b({ref:"container"},'div',_vm.containerProps,false),[_c('autocomplete-input',_vm._g(_vm._b({ref:"input",attrs:{"value":_vm.value},on:{"input":_vm.handleInput,"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }return _vm.handleEnter($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }return _vm.handleEsc($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"tab",9,$event.key,"Tab")){ return null; }return _vm.handleTab($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"up",38,$event.key,["Up","ArrowUp"])){ return null; }$event.preventDefault();return _vm.handleUp($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"down",40,$event.key,["Down","ArrowDown"])){ return null; }$event.preventDefault();return _vm.handleDown($event)}],"focus":_vm.handleFocus,"blur":_vm.handleBlur}},'autocomplete-input',_vm.inputProps,false),_vm.$listeners)),(_vm.results.length)?_c('ul',_vm._b({ref:"resultList",on:{"click":_vm.handleResultClick,"mousedown":function($event){$event.preventDefault();}}},'ul',_vm.resultListProps,false),[_vm._l((_vm.results),function(result,index){return [_vm._t("result",[_c('li',_vm._b({key:_vm.resultProps[index].id},'li',_vm.resultProps[index],false),[_vm._v(" "+_vm._s(_vm.getResultValue(result))+" ")])],{"result":result,"props":_vm.resultProps[index]})]})],2):_vm._e()],1)}
var staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/autocomplete.vue?vue&type=template&id=12f44c8c&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"7b388e96-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/formelements/form-input.vue?vue&type=template&id=5ed93f00&
var form_inputvue_type_template_id_5ed93f00_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('input',_vm._g(_vm._b({staticClass:"form-input"},'input',_vm.$attrs,false),Object.assign({}, _vm.$listeners,
    {input: function (event) { return _vm.$emit('input', event.target.value); }})))}
var form_inputvue_type_template_id_5ed93f00_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/formelements/form-input.vue?vue&type=template&id=5ed93f00&

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
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
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

// CONCATENATED MODULE: ./vue/components/formelements/form-input.vue

var script = {}


/* normalize component */

var component = normalizeComponent(
  script,
  form_inputvue_type_template_id_5ed93f00_render,
  form_inputvue_type_template_id_5ed93f00_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var form_input = (component.exports);
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

  components: {
    'autocomplete-input': form_input,
  },
  props: {
    value: {
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

  beforeDestroy() {
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
      this.$emit('input', value);
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
      this.$emit('input', '');
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
        this.$emit('input', this.getResultValue(selectedResult));
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

// CONCATENATED MODULE: ./vue/components/autocomplete.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_autocompletevue_type_script_lang_js_ = (autocompletevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/autocomplete.vue





/* normalize component */

var autocomplete_component = normalizeComponent(
  components_autocompletevue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var autocomplete = (autocomplete_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"7b388e96-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/formelements/datepicker.vue?vue&type=template&id=437f3c9e&
var datepickervue_type_template_id_437f3c9e_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',_vm._b({},'div',_vm.rootProps,false),[(_vm.hasInput)?_c('date-input',_vm._b({ref:"input",attrs:{"value":_vm.selectedDate},on:{"input":_vm.handleInput,"toggle-datepicker":_vm.toggleDatepicker}},'date-input',_vm.inputProps,false)):_vm._e(),_c('div',_vm._b({ref:"calendar",staticClass:"calendar",class:_vm.align === 'left' ? 'align-left' : 'align-right'},'div',_vm.calendarProps,false),[_c('div',{staticClass:"calendar-nav navbar"},[_c('button',{staticClass:"btn btn-action btn-link btn-large prvMon",on:{"click":function($event){$event.stopPropagation();return _vm.previousMonth($event)}}}),_c('div',{staticClass:"month navbar-primary"},[_vm._v(_vm._s(_vm.monthLabel)+" "+_vm._s(_vm.year))]),_c('button',{staticClass:"btn btn-action btn-link btn-large nxtMon",on:{"click":function($event){$event.stopPropagation();return _vm.nextMonth($event)}}})]),_c('div',{staticClass:"calendar-container"},[_c('div',{staticClass:"calendar-header"},_vm._l((_vm.weekdays),function(weekday){return _c('div',{staticClass:"calendar-date"},[_vm._v(_vm._s(weekday))])}),0),_c('div',{staticClass:"calendar-body"},_vm._l((_vm.days),function(day){return _c('div',{key:day.getTime(),staticClass:"calendar-date text-center",class:['prev-month', '', 'next-month'][day.getMonth() - _vm.month + 1]},[_c('button',{staticClass:"date-item",class:{
                            'active': _vm.selectedDate && day.getTime() === _vm.selectedDate.getTime(),
                            'date-today': day.getTime() === _vm.today.getTime()
                        },attrs:{"type":"button","disabled":(_vm.validFrom && _vm.validFrom) > day || (_vm.validUntil && _vm.validUntil < day)},on:{"click":function($event){$event.stopPropagation();(_vm.validFrom && _vm.validFrom) > day || (_vm.validUntil && _vm.validUntil < day) ? null : _vm.selectDate(day)}}},[_vm._v(_vm._s(day.getDate()))])])}),0)])])],1)}
var datepickervue_type_template_id_437f3c9e_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/formelements/datepicker.vue?vue&type=template&id=437f3c9e&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"7b388e96-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/formelements/date-input.vue?vue&type=template&id=1d121611&
var date_inputvue_type_template_id_1d121611_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"date-input"},[_c('div',{staticClass:"input-group",style:(_vm.computedStyles)},[(_vm.dateString)?_c('div',{staticClass:"form-input"},[_c('span',{staticClass:"chip"},[_vm._v(" "+_vm._s(_vm.dateString)+" "),_c('a',{staticClass:"btn btn-clear",attrs:{"href":"#","aria-label":"Close","role":"button"},on:{"click":function($event){$event.preventDefault();return _vm.handleClear($event)}}})])]):_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.inputString),expression:"inputString"}],staticClass:"form-input",attrs:{"type":"text","autocomplete":"off"},domProps:{"value":(_vm.inputString)},on:{"blur":_vm.handleBlur,"input":function($event){if($event.target.composing){ return; }_vm.inputString=$event.target.value}}}),(_vm.showButton)?_c('button',{staticClass:"btn webfont-icon-only btn-primary input-group-btn",attrs:{"type":"button"},on:{"click":function($event){$event.stopPropagation();return _vm.$emit('toggle-datepicker')}}},[_vm._v("")]):_vm._e()])])}
var date_inputvue_type_template_id_1d121611_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/formelements/date-input.vue?vue&type=template&id=1d121611&

// CONCATENATED MODULE: ./vue/util/date-functions.js
/* harmony default export */ var date_functions = ({

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
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/formelements/date-input.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
            this.inputString = value ? date_functions.formatDate(value, this.outputFormat) : '';
        }
    },

    computed: {
        dateString () {
            return this.value ? date_functions.formatDate(this.value, this.outputFormat) : '';
        },
        computedStyles() {
            return {
                width: '100%'
            }
        }
    },

    methods: {
        handleBlur () {
            let date = date_functions.parseDate(this.inputString, this.inputFormat);
            this.$emit('input', date || null);
        },
        handleClear () {
            this.$emit('input', null);
        }
    }
});

// CONCATENATED MODULE: ./vue/components/formelements/date-input.vue?vue&type=script&lang=js&
 /* harmony default export */ var formelements_date_inputvue_type_script_lang_js_ = (date_inputvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/formelements/date-input.vue





/* normalize component */

var date_input_component = normalizeComponent(
  formelements_date_inputvue_type_script_lang_js_,
  date_inputvue_type_template_id_1d121611_render,
  date_inputvue_type_template_id_1d121611_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var date_input = (date_input_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/formelements/datepicker.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
            selectedDate: null,
            expanded: !this.hasInput,
            align: 'left'
        };
    },

    watch: {
        value (newValue) {
            this.year = (newValue || this.today).getFullYear();
            this.month = (newValue || this.today).getMonth();
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
            document.body.addEventListener('click', this.handleBodyClick);
        }
        this.year = (this.value || this.today).getFullYear();
        this.month = (this.value || this.today).getMonth();
        this.selectedDate = this.value ? new Date(this.value.getFullYear(), this.value.getMonth(), this.value.getDate()) : null;
    },
    beforeDestroy() {
        if(this.hasInput) {
            document.body.removeEventListener('click', this.handleBodyClick);
        }
    },

    methods: {
        previousMonth() {
            const d = new Date(this.year, this.month - 1, 1);
            this.month = d.getMonth();
            this.year = d.getFullYear();
            this.$emit("month-change");
        },
        nextMonth() {
            const d = new Date(this.year, this.month + 1, 1);
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
        handleBodyClick() {
            this.expanded = false;
        },
        handleInput (date) {
            this.selectedDate = date;
            this.$emit('input', date);
        }
    }
});

// CONCATENATED MODULE: ./vue/components/formelements/datepicker.vue?vue&type=script&lang=js&
 /* harmony default export */ var formelements_datepickervue_type_script_lang_js_ = (datepickervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/formelements/datepicker.vue





/* normalize component */

var datepicker_component = normalizeComponent(
  formelements_datepickervue_type_script_lang_js_,
  datepickervue_type_template_id_437f3c9e_render,
  datepickervue_type_template_id_437f3c9e_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var datepicker = (datepicker_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"7b388e96-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/confirm.vue?vue&type=template&id=572f269a&
var confirmvue_type_template_id_572f269a_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{ref:"container",staticClass:"modal modal-sm",class:{ active: _vm.show },on:{"keydown":function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }$event.stopPropagation();return _vm.cancel($event)}}},[_c('a',{staticClass:"modal-overlay",attrs:{"href":"#close"},on:{"click":function($event){$event.preventDefault();return _vm.cancel($event)}}}),_c('div',{staticClass:"modal-container"},[(_vm.title)?_c('div',{staticClass:"modal-header bg-error text-light"},[_c('div',{staticClass:"modal-title h5"},[_vm._v(_vm._s(_vm.title))])]):_vm._e(),_c('div',{staticClass:"modal-body"},[_c('div',{staticClass:"content"},[_vm._v(" "+_vm._s(_vm.message)+" ")])]),_c('div',{staticClass:"modal-footer"},[_c('button',{ref:"okButton",staticClass:"btn mr-2",class:_vm.options.okClass,attrs:{"type":"button"},on:{"click":function($event){$event.preventDefault();return _vm.ok($event)}}},[_vm._v(_vm._s(_vm.options.okLabel))]),_c('button',{staticClass:"btn btn-link",attrs:{"type":"button"},on:{"click":function($event){$event.preventDefault();return _vm.cancel($event)}}},[_vm._v(_vm._s(_vm.options.cancelLabel))])])])])}
var confirmvue_type_template_id_572f269a_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/confirm.vue?vue&type=template&id=572f269a&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/confirm.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* heavily inspired by https://gist.github.com/eolant/ba0f8a5c9135d1a146e1db575276177d */

/* harmony default export */ var confirmvue_type_script_lang_js_ = ({
    name: 'confirm',

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
            okLabel: "Ok",
            cancelLabel: "Cancel",
            okClass: "btn-success"
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
            this.$nextTick(() => this.$refs.okButton.focus());
            this.options = Object.assign(this.options, options || {});
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        ok () {
            this.show = false;
            this.resolve(true);
        },
        cancel () {
            this.show = false;
            this.resolve(false);
        }
    }
});

// CONCATENATED MODULE: ./vue/components/confirm.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_confirmvue_type_script_lang_js_ = (confirmvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/confirm.vue





/* normalize component */

var confirm_component = normalizeComponent(
  components_confirmvue_type_script_lang_js_,
  confirmvue_type_template_id_572f269a_render,
  confirmvue_type_template_id_572f269a_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var components_confirm = (confirm_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"7b388e96-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/alert.vue?vue&type=template&id=77cd4c1f&
var alertvue_type_template_id_77cd4c1f_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{ref:"container",staticClass:"modal modal-sm",class:{ active: _vm.show }},[_c('div',{staticClass:"modal-overlay"}),_c('div',{staticClass:"modal-container"},[(_vm.title)?_c('div',{staticClass:"modal-header bg-error text-light"},[_c('div',{staticClass:"modal-title h5"},[_vm._v(_vm._s(_vm.title))])]):_vm._e(),_c('div',{staticClass:"modal-body"},[_c('div',{staticClass:"content"},[_vm._v(" "+_vm._s(_vm.message)+" ")])]),_c('div',{staticClass:"modal-footer"},[_c('button',{ref:"button",staticClass:"btn",class:_vm.options.buttonClass,attrs:{"type":"button"},on:{"click":function($event){$event.preventDefault();return _vm.ok($event)}}},[_vm._v(_vm._s(_vm.options.label))])])])])}
var alertvue_type_template_id_77cd4c1f_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/alert.vue?vue&type=template&id=77cd4c1f&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/alert.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var alertvue_type_script_lang_js_ = ({
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

// CONCATENATED MODULE: ./vue/components/alert.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_alertvue_type_script_lang_js_ = (alertvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/alert.vue





/* normalize component */

var alert_component = normalizeComponent(
  components_alertvue_type_script_lang_js_,
  alertvue_type_template_id_77cd4c1f_render,
  alertvue_type_template_id_77cd4c1f_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var components_alert = (alert_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"7b388e96-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/formelements/password-input.vue?vue&type=template&id=3030fede&scoped=true&
var password_inputvue_type_template_id_3030fede_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"form-group is-password"},[_c('input',_vm._g(_vm._b({staticClass:"form-input",attrs:{"type":_vm.show ? 'text': 'password'}},'input',_vm.$attrs,false),Object.assign({}, _vm.$listeners,
      {input: function (event) { return _vm.$emit('input', event.target.value); }}))),_c('a',{class:{ 'show': _vm.show },attrs:{"href":"#"},on:{"click":function($event){$event.preventDefault();_vm.show = !_vm.show}}})])}
var password_inputvue_type_template_id_3030fede_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/formelements/password-input.vue?vue&type=template&id=3030fede&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/formelements/password-input.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var password_inputvue_type_script_lang_js_ = ({
    data () { return {
        show: false
    }}
});

// CONCATENATED MODULE: ./vue/components/formelements/password-input.vue?vue&type=script&lang=js&
 /* harmony default export */ var formelements_password_inputvue_type_script_lang_js_ = (password_inputvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./vue/components/formelements/password-input.vue?vue&type=style&index=0&id=3030fede&scoped=true&lang=scss&
var password_inputvue_type_style_index_0_id_3030fede_scoped_true_lang_scss_ = __webpack_require__("cb59");

// CONCATENATED MODULE: ./vue/components/formelements/password-input.vue






/* normalize component */

var password_input_component = normalizeComponent(
  formelements_password_inputvue_type_script_lang_js_,
  password_inputvue_type_template_id_3030fede_scoped_true_render,
  password_inputvue_type_template_id_3030fede_scoped_true_staticRenderFns,
  false,
  null,
  "3030fede",
  null
  
)

/* harmony default export */ var password_input = (password_input_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"7b388e96-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/pagination.vue?vue&type=template&id=a3a76d5c&
var paginationvue_type_template_id_a3a76d5c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('ul',{staticClass:"pagination"},[(_vm.showNavButtons)?_c('li',{staticClass:"page-item",class:{ disabled: _vm.currentPage <= 1 }},[_c('a',{staticClass:"menu-item",attrs:{"tabindex":"-1"},on:{"click":_vm.prevPage}},[_vm._v(_vm._s(_vm.prevText))])]):_vm._e(),_vm._l((_vm.pagesToShow),function(page,idx){return _c('li',{key:idx,staticClass:"page-item",class:{active: _vm.currentPage === page}},[(page !== 'dots')?_c('a',{on:{"click":function($event){return _vm.pageClick(page)}}},[_vm._v(_vm._s(page))]):_c('span',[_vm._v("…")])])}),(_vm.showNavButtons)?_c('li',{staticClass:"page-item",class:{ disabled: _vm.currentPage >= _vm.maxPage }},[_c('a',{attrs:{"tabindex":"-1"},on:{"click":_vm.nextPage}},[_vm._v(_vm._s(_vm.nextText))])]):_vm._e()],2)])}
var paginationvue_type_template_id_a3a76d5c_staticRenderFns = []


// CONCATENATED MODULE: ./vue/components/pagination.vue?vue&type=template&id=a3a76d5c&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./vue/components/pagination.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var paginationvue_type_script_lang_js_ = ({
  name: 'Pagination',
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

// CONCATENATED MODULE: ./vue/components/pagination.vue?vue&type=script&lang=js&
 /* harmony default export */ var components_paginationvue_type_script_lang_js_ = (paginationvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./vue/components/pagination.vue





/* normalize component */

var pagination_component = normalizeComponent(
  components_paginationvue_type_script_lang_js_,
  paginationvue_type_template_id_a3a76d5c_render,
  paginationvue_type_template_id_a3a76d5c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var pagination = (pagination_component.exports);
// CONCATENATED MODULE: ./vue/util/simple-fetch.js
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

// CONCATENATED MODULE: ./vue/util/promised-xhr.js
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

// CONCATENATED MODULE: ./vue/util/url-query.js
/* harmony default export */ var url_query = ({
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



// CONCATENATED MODULE: ./vue/build/sample.js














const Components = {
    Autocomplete: autocomplete,
    DatePicker: datepicker,
    Confirm: components_confirm,
    Alert: components_alert,
    PasswordInput: password_input,
    Pagination: pagination
};

const Directives = {
    Focus: Focus,
    Bubble: Bubble
};

const Util = {
    SimpleFetch: SimpleFetch,
    PromisedXhr: PromisedXhr,
    UrlQuery: url_query,
    DateFunctions: date_functions
};


// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js




/***/ })

/******/ });
//# sourceMappingURL=sample.common.js.map