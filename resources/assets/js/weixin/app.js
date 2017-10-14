
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

window.Vue = require('vue');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import App from "./App.vue"
import router from './router'
import {filters} from './filter'
import VueQuillEditor from 'vue-quill-editor'
import  { AlertPlugin } from 'vux'
import  { ConfirmPlugin } from 'vux'
import  { LoadingPlugin } from 'vux'
import  { ToastPlugin } from 'vux'

Vue.use(VueQuillEditor)
Vue.use(AlertPlugin)
Vue.use(ConfirmPlugin)
Vue.use(LoadingPlugin)
Vue.use(ToastPlugin)

Vue.prototype.toast_message = function (message,type='success',time=2000) {
    this.$vux.toast.show({
        type:type,
        text: message,
        time:time
    })
}

Vue.prototype.send_request = function (meth,url,callback,data=null) {
    var self = this;
    axios({
        'method':meth,
        'url':url,
        'data':data
    }).then(function (response) {
        callback(response,self);
    })
}

require("es6-promise").polyfill()

Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key])
})

const FastClick = require('fastclick')
FastClick.attach(document.body)

// //添加响应拦截器
window.axios.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        //请求错误时做些事
        if (error.response) {
            switch (error.response.status) {
                case 401:
                    window.location.href = "/wx";
                    break;
            }
        }
        return Promise.reject(error.response.data);

    });

Vue.component('remote-script', {

    render: function (createElement) {
        var self = this;
        return createElement('script', {
            attrs: {
                type: 'text/javascript',
                src: this.src
            },
            on: {
                load: function (event) {
                    self.$emit('load', event);
                },
                error: function (event) {
                    self.$emit('error', event);
                },
                readystatechange: function (event) {
                    if (this.readyState == 'complete') {
                        self.$emit('load', event);
                    }
                }
            }
        });
    },

    props: {
        src: {
            type: String,
            required: true
        }
    }
});


const app = new Vue({
    el: '#app',
    router,
    render:h=>h(App)
});
