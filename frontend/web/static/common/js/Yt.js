/**
 * 
 * 
 * @class Yt
 * @extends {Base}
 */
class Yt extends Base {
    constructor() {}
    /**
     * 格式化时间
     * @static
     * @param {any} date
     * @param {any} fmt
     * @returns
     * @memberof Yt
     * @example const date = formatDate(new Date(), "yyyy-MM-dd hh:mm:ss");
     */
    static formatDate(date, fmt) {
        if (/(y+)/.test(fmt)) {
            fmt = fmt.replace(
                RegExp.$1,
                (date.getFullYear() + "").substr(4 - RegExp.$1.length)
            );
        }
        let o = {
            "M+": date.getMonth() + 1,
            "d+": date.getDate(),
            "h+": date.getHours(),
            "m+": date.getMinutes(),
            "s+": date.getSeconds()
        };
        for (let k in o) {
            if (new RegExp(`(${k})`).test(fmt)) {
                let str = o[k] + "";
                fmt = fmt.replace(
                    RegExp.$1,
                    RegExp.$1.length === 1 ? str : ("00" + str).substr(str.length)
                );
            }
        }
        return fmt;
    }
    /**
     * @static
     * @param {any} name
     * @returns
     * @memberof Yt
     */
    static getCookie(name) {
        var arr,
            reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
        if ((arr = document.cookie.match(reg))) return unescape(arr[2]);
        return null;
    }
    /**
     *
     * @static
     * @param {any} name
     * @memberof Yt
     */
    static delCookie(name) {
        Yt.setCookie(name, '', '1s');
    }
    /**
     * @static
     * @param {any} name
     * @param {any} value
     * @param {any} time "100d", "500h"
     * @memberof Yt
     */
    static setCookie(name, value, time = '') {
        if (!time) {
            var strsec= Yt.getsec('100d');
        } else {
            var strsec = Yt.getsec(time);
        }
        var exp = new Date();
        exp.setTime(exp.getTime() + strsec * 1);
        document.cookie =
            name + "=" + escape(value) + ";expires=" + exp.toGMTString();
    }
    /**
     * @static
     * @param {any} str
     * @returns
     * @memberof Yt
     */
    static getsec(str) {
        var str1 = str.substring(1, str.length) * 1;
        var str2 = str.substring(0, 1);
        if (str2 == "s") {
            return str1 * 1000;
        } else if (str2 == "h") {
            return str1 * 60 * 60 * 1000;
        } else if (str2 == "d") {
            return str1 * 24 * 60 * 60 * 1000;
        }
    }

    /**
     * 把秒数转成hh:mm:ss
     * @static
     * @param {any} value
     * @returns
     * @memberof Yt
     */
    static formatSeconds(value) {
        var secondTime = parseInt(value);// 秒
        var minuteTime = 0;// 分
        var hourTime = 0;// 小时
        if(secondTime > 60) {//如果秒数大于60，将秒数转换成整数
            //获取分钟，除以60取整数，得到整数分钟
            minuteTime = parseInt(secondTime / 60);
            //获取秒数，秒数取佘，得到整数秒数
            secondTime = parseInt(secondTime % 60);
            //如果分钟大于60，将分钟转换成小时
            if(minuteTime > 60) {
                //获取小时，获取分钟除以60，得到整数小时
                hourTime = parseInt(minuteTime / 60);
                //获取小时后取佘的分，获取分钟除以60取佘的分
                minuteTime = parseInt(minuteTime % 60);
            }
        }
        var result = "" + parseInt(secondTime) + "秒";

        if(minuteTime > 0) {
            result = "" + parseInt(minuteTime) + "分" + result;
        }
        if(hourTime > 0) {
            result = "" + parseInt(hourTime) + "小时" + result;
        }
        return result;
    }
    static secondsTurnToTime(value) {
        var secondTime = parseInt(value);// 秒
        var minuteTime = 0;// 分
        var hourTime = 0;// 小时
        if(secondTime > 60) {//如果秒数大于60，将秒数转换成整数
            //获取分钟，除以60取整数，得到整数分钟
            minuteTime = parseInt(secondTime / 60);
            //获取秒数，秒数取佘，得到整数秒数
            secondTime = parseInt(secondTime % 60);
            //如果分钟大于60，将分钟转换成小时
            if(minuteTime > 60) {
                //获取小时，获取分钟除以60，得到整数小时
                hourTime = parseInt(minuteTime / 60);
                //获取小时后取佘的分，获取分钟除以60取佘的分
                minuteTime = parseInt(minuteTime % 60);
            }
        }
        return Yt.turnToTwo(hourTime) + ":"+ Yt.turnToTwo(minuteTime)+ ":" + Yt.turnToTwo(secondTime)
    }
    
    static turnToTwo(value){
        return value<10? "0" + value : "" + value
    }


    /**
     * axios 生成参数方法
     * @static
     * @param {object} data
     * @returns {object} params
     * @memberof Yt
     * @example data = {
     *  name: '野兽先辈',
     *  age: 24,
     *  profession: 'student'
     * }
     */
    static axiosParams(data) {
        const params = new URLSearchParams();
        if (data) {
            for (let param in data) {
                params.append(param, data[param]);
            }
        }
        return params;
    }
    /**
     * 封装axios函数,可以在vue实例中直接调用
     * @example const conf = {
     *              url, # 请求的地址,不可为空
     *              success: function (data){ console.log(data) }, # 请求成功后的回调函数,此处data为axios返回的response.data.info ,如果success不是函数,则eval(success) = response.data.info
     *              data # 请求的参数,可为空
     *          }
     *          Yt.axiosRequest(conf);
     * @static
     * @param {Object} conf
     * @memberof Yt
     */
    // 返回的为data中的info
    // static axiosRequest(conf) {
    //     if (conf.url == undefined || !conf.url) throw new Error("说好的url参数尼");
    //     if (conf.data == undefined) conf.data = "";
    //     const param = Yt.axiosParams(conf.data);
    //     axios.post(conf.url, param)
    //         .then((response) => {
    //             if (parseInt(response.data.statusCode) == 1) {
    //                 if (conf.success != undefined && conf.success != "") {
    //                     if (typeof conf.success == "function") {
    //                         conf.success(response.data.info);
    //                     } else {
    //                         eval(conf.success + "=response.data.info");
    //                     }
    //                 }
    //             } else {
    //                 if (response.data.message) {
    //                     Vue.prototype.$message.error(response.data.message);
    //                 } else {
    //                     let info = "";
    //                     _.forEach(response.data.info, (value) => {
    //                         info += value;
    //                     })
    //                     Vue.prototype.$message.error(info);
    //                 }
    //             }
    //         })
    //         .catch((e) => {
    //             Vue.prototype.$message.error('网络请求失败');
    //             console.error('捕获到then里回调函数success或服务器的错误:', e);
    //         })
    // }
    // 返回的为data
    static axiosRequest(conf) {
        if (conf.url == undefined || !conf.url) throw new Error("说好的url参数尼");
        if (conf.data == undefined) conf.data = "";
        const param = Yt.axiosParams(conf.data);
        axios.post(conf.url, param)
            .then((response) => {
                if (parseInt(response.data.statusCode) == 1) {
                    if (conf.success != undefined && conf.success != "") {
                        if (typeof conf.success == "function") {
                            conf.success(response.data);
                        } else {
                            eval(conf.success + "=response.data.info");
                        }
                    }
                } else {
                    if (response.data.message) {
                        // Vue.prototype.$message.error(response.data.message);
                        conf.success(response.data);
                    } else {
                        let info = "";
                        _.forEach(response.data.info, (value) => {
                            info += value;
                        })
                        // Vue.prototype.$message.error(info);
                    }
                }
            })
            .catch((e) => {
                // Vue.prototype.$message.error('网络请求失败');
                console.error('捕获到then里回调函数success或服务器的错误:', e);
            })
    }
    /**
     * 
     * @static
     * @param {any} name 
     * @returns 
     * @memberof Yt
     */
    static queryUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }
    /**
     * 
     * 
     * @static
     * @param {any} url 
     * @memberof Yt
     */
    static goHome(url) {
        if (url) window.location.href = url;
        else window.location.href = window.location.pathname + "?r=asr-robot-dashboard";
    }
    /**
     * 
     * 
     * @static
     * @param {any} param 
     * @memberof Yt
     */
    static go(param) {
        if (typeof param == "number") window.location.history.go(param);
        window.location.href = param;
    }
    /**
     * 获得请求的地址,是常数,后台的controller 必须和前台一样
     * @example  const REQUEST_URL = Yt.getRequestUrl(); // 已注册到全局;
     * 
     * @static
     * @memberof Yt
     */
    static getRequestUrl() {
        const pathname = "/api_" + _.trim(location.pathname, "/");
        const requestUrl = pathname + "?r=" + Yt.getControllerId();
        return requestUrl;
    }
    static getControllerId() {
        return Yt.getRouterParam("controller");
    }

    static getActionId() {
        return Yt.getRouterParam("action");
    }

    static getRouter() {
        return Yt.getRouterParam();
    }

    static getRouterParam(param = false) {
        const router = Yt.queryUrlParam("r");
        let arr = '';
        if (router) {
            arr = router.split("/");
        } else {
            return false;
        }
        
        if (param) {
            if (param == "controller") {
                if (!router) return "index";
                if (arr.length == 1) return arr[0];
                return arr[0];
            } else if (param == "action") {
                if (!router) return "index";
                if (arr.length == 1) return "index";
                return arr[1];
            } else {
                throw new Error("你秀尼🐎呢");
            }

        } else {
            if (!router) return "index/index";
            if (arr.length == 1) return arr[0] + "/index";
            return router;
        }
    }
}
const REQUEST_URL = Yt.getRequestUrl();
const CONTROLLER_ID = Yt.getControllerId();
const ACTION_ID = Yt.getActionId();
/**
 * 把配置信息挂载到Yt上
 */
for (let k in appConf) {
    Yt[k] = appConf[k];
    Yt.prototype[k] = appConf[k];
}
//实现元素跟随鼠标点击拖动
// 引用，写在元素上v-move
Vue.directive("move",{
    bind(el, binding, vnode, oldVnode) {
    const dialogHeaderEl = el.querySelector('.el-dialog__header')
    const dragDom = el.querySelector('.el-dialog')
    dialogHeaderEl.style.cursor = 'move'

    // 获取原有属性 ie dom元素.currentStyle 火狐谷歌 window.getComputedStyle(dom元素, null);
    const sty = dragDom.currentStyle || window.getComputedStyle(dragDom, null)
    
    dialogHeaderEl.onmousedown = (e) => {
        // 鼠标按下，计算当前元素距离可视区的距离
        const disX = e.clientX - dialogHeaderEl.offsetLeft
        const disY = e.clientY - dialogHeaderEl.offsetTop
        
        // 获取到的值带px 正则匹配替换
        let styL, styT

        // 注意在ie中 第一次获取到的值为组件自带50% 移动之后赋值为px
        if(sty.left.includes('%')) {
            styL = +document.body.clientWidth * (+sty.left.replace(/\%/g, '') / 100)
            styT = +document.body.clientHeight * (+sty.top.replace(/\%/g, '') / 100)
        }else {
            styL = +sty.left.replace(/\px/g, '')
            styT = +sty.top.replace(/\px/g, '')
        }
        
        document.onmousemove = function (e) {
            // 通过事件委托，计算移动的距离 
            const l = e.clientX - disX
            const t = e.clientY - disY

            // 移动当前元素  
            dragDom.style.left = `${l + styL}px`
            dragDom.style.top = `${t + styT}px`

            //将此时的位置传出去
            //binding.value({x:e.pageX,y:e.pageY})
        }

        document.onmouseup = function (e) {
            document.onmousemove = null
            document.onmouseup = null
        }
    }  
}
})
function clone(obj) {//深度复制对象
    // Handle the 3 simple types, and null or undefined or function
        if (null == obj || "object" != typeof obj) return obj;
    
        // Handle Date
        if (obj instanceof Date) {
            var copy = new Date();
            copy.setTime(obj.getTime());
            return copy;
    }
    // Handle Array or Object
    if (obj instanceof Array | obj instanceof Object) {
        var copy = (obj instanceof Array)?[]:{};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr))
                copy[attr] = clone(obj[attr]);
        }
        return copy;
    }
    throw new Error("Unable to clone obj! Its type isn't supported.");
}
