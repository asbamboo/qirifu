(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-94a2d358"],{2465:function(t,e,n){"use strict";n.d(e,"b",function(){return o}),n.d(e,"a",function(){return c}),n.d(e,"e",function(){return s}),n.d(e,"d",function(){return u}),n.d(e,"c",function(){return d});var a=n("b775"),r=n("4328"),i=n.n(r);function o(t){return Object(a["a"])({url:"/trade/lists",method:"get",params:t})}function c(){return Object(a["a"])({url:"/trade/channels",method:"get"})}function s(t){return Object(a["a"])({url:"/trade/order",method:"post",data:i.a.stringify(t)})}function u(t){return Object(a["a"])({url:"/trade/auth-url",method:"post",data:i.a.stringify(t)})}function d(t){return Object(a["a"])({url:"/trade/auth-info",method:"post",data:i.a.stringify(t)})}},"386d":function(t,e,n){"use strict";var a=n("cb7c"),r=n("83a1"),i=n("5f1b");n("214f")("search",1,function(t,e,n,o){return[function(n){var a=t(this),r=void 0==n?void 0:n[e];return void 0!==r?r.call(n,a):new RegExp(n)[e](String(a))},function(t){var e=o(n,t,this);if(e.done)return e.value;var c=a(t),s=String(this),u=c.lastIndex;r(u,0)||(c.lastIndex=0);var d=i(c,s);return r(c.lastIndex,u)||(c.lastIndex=u),null===d?-1:d.index}]})},"83a1":function(t,e){t.exports=Object.is||function(t,e){return t===e?0!==t||1/t===1/e:t!=t&&e!=e}},dade:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[t.is_supported?n("el-form",{attrs:{"label-width":"100px;"}},[n("div",{staticClass:"title-container"},[n("h3",{staticClass:"title"},[t._v("自助付款")])]),t._v(" "),n("el-form-item",[n("el-input",{ref:"inputTradePrice",attrs:{placeholder:"请输入支付金额",name:"trade_price",type:"number",pattern:"\\d*"},model:{value:t.form.trade_price,callback:function(e){t.$set(t.form,"trade_price",e)},expression:"form.trade_price"}})],1),t._v(" "),n("el-form-item",[n("el-button",{staticStyle:{width:"100%"},attrs:{type:"primary"},nativeOn:{click:function(e){return e.preventDefault(),t.doPay(e)}}},[t._v("付款")])],1)],1):n("p",[t._v("\n    对不起，系统暂不支持您选择的支付方式\n  ")])],1)},r=[],i=(n("386d"),n("4917"),n("2465")),o=n("4328"),c=n.n(o),s={trade_price:"",pay_type:void 0,user_id:void 0,auth_info:void 0},u={name:"TradeOrder",data:function(){var t=!1,e=window.navigator.userAgent.toLowerCase();return"micromessenger"==e.match(/MicroMessenger/i)?(s.pay_type="wxpay",t=!0):"alipayclient"==e.match(/AlipayClient/i)&&(s.pay_type="alipay",t=!0),s.user_id=this.$route.params.user_id,{form:Object.assign({},s),is_supported:t}},created:function(){this.is_supported&&this.doAuth()},mounted:function(){this.$refs.inputTradePrice.focus()},methods:{doAuth:function(){var t=this;console.log(this.$route.query);var e=!1;if("alipay"==this.form.pay_type&&location.href.search("auth_code")>0&&(e=!0),"wxpay"==this.form.pay_type&&location.href.search("code")>0&&(e=!0),console.log(e,this.form.pay_type,location.href.search("auth_code")),e){var n=this.$route.query;"wxpay"==this.form.pay_type&&(n=c.a.parse(location.search.slice(1))),n.type=this.form.pay_type,console.log(n),Object(i["c"])(n).then(function(e){t.form.auth_info=Object.assign({},e.data)}).catch(function(t){console.log(t)})}else Object(i["d"])({pay_type:this.form.pay_type,redirect_url:location.href}).then(function(t){location.href=t.data.auth_url}).catch(function(t){console.log(t)})},doRequestAlipay:function(t){AlipayJSBridge.call("tradePay",{tradeNO:t},function(t){console.log(t),"9000"==t.resultCode&&this.$message({message:支付成功,showClose:!0})})},doPay:function(){var t=this;Object(i["e"])(this.form).then(function(e){window.AlipayJSBridge?t.doRequestAlipay(e.data.onecd_pay_json.trade_no):document.addEventListener("AlipayJSBridgeReady",function(){this.doRequestAlipay(e.data.onecd_pay_json.trade_no)},!1)}).catch(function(t){console.log(t)})}}},d=u,l=n("2877"),p=Object(l["a"])(d,a,r,!1,null,null,null);e["default"]=p.exports}}]);