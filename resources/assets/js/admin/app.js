/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import App from "./Admin.vue"
import router from './router.js'
import ElementUI from "element-ui"
import * as filters from './filter'
import 'element-ui/lib/theme-default/index.css';

Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key])
})

require("es6-promise").polyfill()

Vue.use(ElementUI);

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router
});
