
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
import VueQuillEditor from 'vue-quill-editor'
Vue.use(VueQuillEditor)

const FastClick = require('fastclick')
FastClick.attach(document.body)

const app = new Vue({
    el: '#app',
    router,
    render:h=>h(App)
});

/*
* 公共方法
* 当前时间格式化
* var time1 = new Date().format("yyyy年MM月dd日 hh点mm分ss秒");
* 输出 2017年10月04日 09点50分00秒
* var time1 = new Date().format("yyyy-MM-dd hh:mm:ss");
* 输出 2017-10-04 01:01:01
*  var time1 = new Date().format("yyyy-MM-dd hh:mm");
* 输出 2017-10-04 01:01
* var time1 = new Date().format("yyyy-MM-dd");
* 输出 2017-10-04
* var time1 = new Date().format("MM-dd");
* 输出 10-04
* */
Date.prototype.format = function(fmt) {
    var o = {
        "M+" : this.getMonth()+1,                 //月份
        "d+" : this.getDate(),                    //日
        "h+" : this.getHours(),                   //小时
        "m+" : this.getMinutes(),                 //分
        "s+" : this.getSeconds(),                 //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S"  : this.getMilliseconds()             //毫秒
    };
    if(/(y+)/.test(fmt)) {
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
    for(var k in o) {
        if(new RegExp("("+ k +")").test(fmt)){
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        }
    }
    return fmt;
}