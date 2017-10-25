/*模板里用的函数*/
const filters = {
    date: function(date, pattern) {
        if (date == undefined) {
            date = new Date();
        } else {
            date = new Date(date*1000)
        }
        if (pattern == undefined) {
            // pattern = "yyyy-MM-dd hh:mm:ss";
            pattern = "yyyy-MM-dd hh:mm";
        }
        var o = {
            "M+": date.getMonth() + 1,
            "d+": date.getDate(),
            "h+": date.getHours(),
            "m+": date.getMinutes(),
            "s+": date.getSeconds(),
            "q+": Math.floor((date.getMonth() + 3) / 3),
            "S": date.getMilliseconds()
        }
        if (/(y+)/.test(pattern)) {
            pattern = pattern.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
        }
        for (var k in o) {
            if (new RegExp("(" + k + ")").test(pattern)) {
                pattern = pattern.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
            }
        }
        return pattern;
    },
    // 将时间戳 转换为 X天X小时X分钟
    formatDuring: function(mss){
        var days = parseInt(mss / ( 60 * 60 * 24));
        var hours = parseInt((mss % ( 60 * 60 * 24)) / ( 60 * 60));
        var minutes = parseInt((mss % ( 60 * 60)) /  60 );
        var seconds = mss %  60  ;
        return days + " 天 " + hours + " 小时 " + minutes + " 分钟 ";
    },
    //去掉html标签
    delHtmlTag: function(str){
        return str.replace(/<[^>]+>/g,"");//去掉所有的html标记
    }

}

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

export { filters }