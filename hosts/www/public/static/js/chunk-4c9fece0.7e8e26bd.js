(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-4c9fece0"],{"5fe8":function(a,t,n){"use strict";n.r(t);var c=function(){var a=this,t=a.$createElement,n=a._self._c||t;return n("div",{staticClass:"app-container"},[n("div",{staticClass:"print-data-container",style:a.containerStyle,attrs:{id:"print_data_container"}},[n("div",{staticClass:"logo-container"},[n("el-image",{attrs:{src:a.alipay_jpg}}),a._v(" "),n("el-image",{attrs:{src:a.wxpay_jpg}})],1),a._v(" "),n("div",{staticClass:"qrcode-container"},[n("canvas",{attrs:{id:"qrcode_canvas",data:a.qrcode}})]),a._v(" "),n("div",{staticClass:"faciltator-desc"},[n("p",[a._v("感谢"+a._s(a.faciltator)+"提供技术支持")])])]),a._v(" "),n("el-divider"),a._v(" "),n("router-link",{attrs:{target:"_blank",to:"/window-print"}},[n("el-button",{attrs:{type:"primary",disabled:a.ajax}},[a._v("打印")])],1)],1)},e=[],i=n("a14d"),o=n.n(i),r=n("d9d9"),s=n.n(r),d=n("c860"),l=n.n(d),f=n("d055"),p=n.n(f),u=n("b775");function v(){return Object(u["a"])({url:"/qrcode/get-data",method:"get"})}var g={name:"InformationQrcode",data:function(){return{alipay_jpg:o.a,wxpay_jpg:s.a,containerStyle:{background:"url("+l.a+")"},qrcode:"",faciltator:"www.asbamboo.com",ajax:!1}},created:function(){this.fetchData()},watch:{qrcode:function(){this.generateQrcodeCanvas()}},methods:{fetchData:function(){var a=this;this.ajax=!0,v().then(function(t){a.qrcode=t.data.qrcode,t.data.faciltator&&(a.faciltator=t.data.faciltator),a.ajax=!1}).catch(function(t){console.log(t),a.ajax=!1})},generateQrcodeCanvas:function(){p.a.toCanvas(document.getElementById("qrcode_canvas"),this.qrcode,function(a){console.log(a)})}}},_=g,h=(n("f0a8"),n("2877")),m=Object(h["a"])(_,c,e,!1,null,null,null);t["default"]=m.exports},a14d:function(a,t,n){a.exports=n.p+"static/img/alipay.ff0c4875.jpg"},c860:function(a,t,n){a.exports=n.p+"static/img/bg.26adc257.jpg"},d9d9:function(a,t,n){a.exports=n.p+"static/img/wxpay.90089a89.jpg"},eda6:function(a,t,n){},f0a8:function(a,t,n){"use strict";var c=n("eda6"),e=n.n(c);e.a}}]);