(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e75c7f86"],{"26ef":function(e,t,a){},3052:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("router-link",{attrs:{to:"/merchant/channel/"+e.merchant.seq}},[a("el-button",{attrs:{type:"primary",size:"small",icon:"el-icon-edit"}},[e._v("\n      支付通道管理\n    ")])],1),e._v(" "),a("el-divider"),e._v(" "),a("el-collapse",{model:{value:e.active_collapse,callback:function(t){e.active_collapse=t},expression:"active_collapse"}},[a("el-collapse-item",{attrs:{name:"collapse-item-com"}},[a("template",{slot:"title"},[a("div",[a("h4",[e._v("企业/个体工商户")])])]),e._v(" "),a("div",[e._v("简称："+e._s(e.merchant.name))]),e._v(" "),a("div",[e._v("全称："+e._s(e.merchant.fullname))]),e._v(" "),a("div",[e._v("行业："+e._s(e.merchant.profession))]),e._v(" "),a("div",[e._v("经营类目："+e._s(e.merchant.businecate))]),e._v(" "),a("div",[e._v("统一社会信用代码："+e._s(e.merchant.code))]),e._v(" "),a("div",[e._v("注册地址："+e._s(e.merchant.address_register))]),e._v(" "),a("div",[e._v("实际经营地址："+e._s(e.merchant.address_actual))]),e._v(" "),a("div",[e._v("添加日期："+e._s(e.merchant.create_ymdhis))]),e._v(" "),a("div",[e._v("修改日期："+e._s(e.merchant.update_ymdhis))])],2),e._v(" "),a("el-collapse-item",{attrs:{name:"collapse-item-linkman"}},[a("template",{slot:"title"},[a("div",[a("h4",[e._v("联系人")])])]),e._v(" "),a("div",[e._v("姓名："+e._s(e.merchant.link_man))]),e._v(" "),a("div",[e._v("联系电话："+e._s(e.merchant.link_phone))]),e._v(" "),a("div",[e._v("email："+e._s(e.merchant.link_email))])],2),e._v(" "),a("el-collapse-item",{attrs:{name:"collapse-item-legalman"}},[a("template",{slot:"title"},[a("div",[a("h4",[e._v("法定代表人")])])]),e._v(" "),a("div",[e._v("证件类型："+e._s(e.merchant.legal_id_type))]),e._v(" "),a("div",[e._v("证件号码："+e._s(e.merchant.legal_id_no))]),e._v(" "),a("div",[e._v("证件有效期："+e._s(e.merchant.legal_id_indate))])],2),e._v(" "),a("el-collapse-item",{attrs:{name:"collapse-item-bank"}},[a("template",{slot:"title"},[a("div",[a("h4",[e._v("结算账户")])])]),e._v(" "),a("div",[e._v("账户类型："+e._s(e.merchant.bank_account_type))]),e._v(" "),a("div",[e._v("开户银行："+e._s(e.merchant.bank_name))]),e._v(" "),a("div",[e._v("开户名称："+e._s(e.merchant.bank_account_name))]),e._v(" "),a("div",[e._v("结算账号："+e._s(e.merchant.bank_account_no))])],2),e._v(" "),a("el-collapse-item",{attrs:{title:"上传资料",name:"collapse-item-files"}},[a("template",{slot:"title"},[a("div",[a("h4",[e._v("上传资料")])])]),e._v(" "),e._l(e.merchant.files,function(t){return a("div",{staticClass:"merchant-image-box"},[a("a",{attrs:{href:t.url,target:"_blank"}},[a("el-image",{staticStyle:{width:"100%",height:"200px",border:"#eeeeee"},attrs:{src:t.url,fit:"scale-down"}}),e._v("\n            "+e._s(t.name)+"\n          ")],1)])})],2)],1)],1)},c=[],i=a("8492"),l={link_man:"",link_phone:"",link_email:"",name:"",fullname:"",profession:"",businecate:"",code:"",address_register:"",address_actual:"",legal_id_type:"身份证",legal_id_no:"",legal_id_indate:"",bank_account_type:"",bank_name:"",bank_account_name:"",bank_account_no:"",create_ymdhis:"",update_ymdhis:""},s={name:"MerchantDetail",data:function(){return{merchant:Object.assign({},l),active_collapse:["collapse-item-com","collapse-item-files"]}},created:function(){this.fetchData(this.$route.params.seq)},methods:{fetchData:function(e){var t=this;Object(i["c"])(e).then(function(e){t.merchant=e.data}).catch(function(e){console.log(e)})}}},_=s,r=(a("a77d"),a("2877")),m=Object(r["a"])(_,n,c,!1,null,null,null);t["default"]=m.exports},8492:function(e,t,a){"use strict";a.d(t,"d",function(){return c}),a.d(t,"c",function(){return i}),a.d(t,"a",function(){return l}),a.d(t,"e",function(){return s}),a.d(t,"b",function(){return _});var n=a("b775");function c(e){return Object(n["a"])({url:"/merchant/lists",method:"get",params:e})}function i(e){return Object(n["a"])({url:"/merchant/detail",method:"get",params:{seq:e}})}function l(e){return Object(n["a"])({url:"/merchant/channel",method:"get",params:{seq:e}})}function s(){return Object(n["a"])({url:"/merchant/channel-search-options",method:"get"})}function _(e){return Object(n["a"])({url:"/merchant/channel-lists",method:"get",params:e})}},a77d:function(e,t,a){"use strict";var n=a("26ef"),c=a.n(n);c.a}}]);