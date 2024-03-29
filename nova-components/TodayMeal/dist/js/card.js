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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(6);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
  Vue.component('today-meal', __webpack_require__(2));
});

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(3)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Card.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b9bc2c0a", Component.options)
  } else {
    hotAPI.reload("data-v-b9bc2c0a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 3 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
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
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["card"],

  mounted: function mounted() {
    //
  }
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("card", { staticClass: "flex flex-col justify-start pl-4 pt-4" }, [
    _c("h4", { staticClass: "font-bold text-primary text-2xl" }, [
      _vm._v("Today's Menu")
    ]),
    _vm._v(" "),
    _vm.card.menu
      ? _c("div", { staticClass: "px-3 py-3" }, [
          _c("h1", { staticClass: "text-left text-lg text-80 font-light" }, [
            _vm._v("\n      Plat Principal :\n      "),
            _c("span", { staticClass: "font-semibold" }, [
              _vm._v(_vm._s(_vm.card.menu.main_dish))
            ])
          ]),
          _vm._v(" "),
          _c("h1", { staticClass: "text-left text-lg text-80 font-light" }, [
            _vm._v("\n      Plat Secondaire :\n      "),
            _c("span", { staticClass: "font-semibold" }, [
              _vm._v(_vm._s(_vm.card.menu.secondary_dish))
            ])
          ]),
          _vm._v(" "),
          _c("h1", { staticClass: "text-left text-lg text-80 font-light" }, [
            _vm._v("\n      Dessert : "),
            _c("span", { staticClass: "font-semibold" }, [
              _vm._v(_vm._s(_vm.card.menu.dessert))
            ])
          ])
        ])
      : _c("div", [
          _c(
            "h1",
            { staticClass: "text-centre text-lg text-80 font-semibold" },
            [_vm._v("\n      No Information For Now\n    ")]
          )
        ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-b9bc2c0a", module.exports)
  }
}

/***/ }),
/* 6 */
/***/ (function(module, exports) {

throw new Error("Module build failed: ModuleBuildError: Module build failed: Error: Node Sass does not yet support your current environment: Windows 64-bit with Unsupported runtime (93)\nFor more information on which environments are supported please see:\nhttps://github.com/sass/node-sass/releases/tag/v4.14.1\n    at module.exports (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\node-sass\\lib\\binding.js:13:13)\n    at Object.<anonymous> (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\node-sass\\lib\\index.js:14:35)\n    at Module._compile (node:internal/modules/cjs/loader:1126:14)\n    at Object.Module._extensions..js (node:internal/modules/cjs/loader:1180:10)\n    at Module.load (node:internal/modules/cjs/loader:1004:32)\n    at Function.Module._load (node:internal/modules/cjs/loader:839:12)\n    at Module.require (node:internal/modules/cjs/loader:1028:19)\n    at require (node:internal/modules/cjs/helpers:102:18)\n    at Object.<anonymous> (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\sass-loader\\lib\\loader.js:3:14)\n    at Module._compile (node:internal/modules/cjs/loader:1126:14)\n    at Object.Module._extensions..js (node:internal/modules/cjs/loader:1180:10)\n    at Module.load (node:internal/modules/cjs/loader:1004:32)\n    at Function.Module._load (node:internal/modules/cjs/loader:839:12)\n    at Module.require (node:internal/modules/cjs/loader:1028:19)\n    at require (node:internal/modules/cjs/helpers:102:18)\n    at loadLoader (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\loadLoader.js:18:17)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at runLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:365:2)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\NormalModule.js:195:19\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:367:11\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:172:11\n    at loadLoader (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\loadLoader.js:32:11)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:165:10)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:176:18\n    at loadLoader (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\loadLoader.js:47:3)\n    at iteratePitchingLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:169:2)\n    at runLoaders (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\loader-runner\\lib\\LoaderRunner.js:365:2)\n    at NormalModule.doBuild (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\NormalModule.js:182:3)\n    at NormalModule.build (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\NormalModule.js:275:15)\n    at Compilation.buildModule (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\Compilation.js:157:10)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\Compilation.js:460:10\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\NormalModuleFactory.js:243:5\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\NormalModuleFactory.js:94:13\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\tapable\\lib\\Tapable.js:268:11\n    at NormalModuleFactory.<anonymous> (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\CompatibilityPlugin.js:52:5)\n    at NormalModuleFactory.applyPluginsAsyncWaterfall (E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\tapable\\lib\\Tapable.js:272:13)\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\NormalModuleFactory.js:69:10\n    at E:\\Programming\\Work\\PFE\\nova-components\\TodayMeal\\node_modules\\webpack\\lib\\NormalModuleFactory.js:196:7\n    at processTicksAndRejections (node:internal/process/task_queues:78:11)");

/***/ })
/******/ ]);